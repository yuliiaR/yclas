<?php

/**
* Stripe class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@open-classifieds.com>
* @license GPL v3
*/

class Controller_Stripe extends Controller{
    
    /**
     * gets the payment token from stripe and marks order as paid
     */
    public function action_pay()
    { 
        $this->auto_render = FALSE;

        $id_order = $this->request->param('id');

        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->limit(1)->find();

        if ($order->loaded())
        {

            if ( isset( $_POST[ 'stripeToken' ] ) ) 
            {
                //its a fraud...lets let him know
                if ( $order->is_fraud() === TRUE )
                {
                    Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                }

                // include class vendor
                require Kohana::find_file('vendor/stripe', 'init');

                // Set your secret key: remember to change this to your live secret key in production
                // See your keys here https://manage.stripe.com/account
                \Stripe\Stripe::setApiKey(Core::config('payment.stripe_private'));

                // Get the credit card details submitted by the form
                $token = Core::post('stripeToken');

                // email
                $email = Core::post('stripeEmail');

                // Create the charge on Stripe's servers - this will charge the user's card
                try 
                {
                    $charge = \Stripe\Charge::create(array(
                                                        "amount"    => StripeKO::money_format($order->amount), // amount in cents, again
                                                        "currency"  => $order->currency,
                                                        "card"      => $token,
                                                        "description" => $order->description,
                                                        "metadata"    => array("id_order" => $order->id_order))
                                                    );
                }
                catch(Exception $e) 
                {
                    // The card has been declined
                    Kohana::$log->add(Log::ERROR, 'Stripe The card has been declined');
                    Alert::set(Alert::ERROR, 'Stripe The card has been declined');
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                }
                
                //mark as paid
                $order->confirm_payment('stripe',Core::post('stripeToken'));
                
                //redirect him to his ads
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'orders')));
            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
            }
            
        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
        }
    }


    /**
     * gets the payment token from stripe and marks order as paid. Methos for application fee
     */
    public function action_payconnect()
    { 
        //TODO only if stripe connect enabled
        
        $this->auto_render = FALSE;

        $id_order = $this->request->param('id');

        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->where('id_product','=',Model_Order::PRODUCT_AD_SELL)
                       ->limit(1)->find();

        if ($order->loaded())
        {

            if ( isset( $_POST[ 'stripeToken' ] ) ) 
            {
                //its a fraud...lets let him know
                if ( $order->is_fraud() === TRUE )
                {
                    Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                }

                // include class vendor
                require Kohana::find_file('vendor/stripe', 'init');

                // Set your secret key: remember to change this to your live secret key in production
                // See your keys here https://manage.stripe.com/account
                \Stripe\Stripe::setApiKey(Core::config('payment.stripe_private'));

                // Get the credit card details submitted by the form
                $token = Core::post('stripeToken');

                // email
                $email = Core::post('stripeEmail');

                // Create the charge on Stripe's servers - this will charge the user's card
                try 
                {   
                    //in case memberships the fee may be set on the plan ;)
                    $fee = NULL;
                    if ( $order->ad->user->subscription()->loaded() )
                        $fee = $order->ad->user->subscription()->plan->marketplace_fee;

                    $application_fee = StripeKO::application_fee($order->amount, $fee);

                    //we charge the fee only if its not admin
                    if (! $order->ad->user->is_admin())
                    {
                        $charge = \Stripe\Charge::create(array(
                                                        "amount"    => StripeKO::money_format($order->amount), // amount in cents, again
                                                        "currency"  => $order->currency,
                                                        "card"      => $token,
                                                        "description" => $order->description,
                                                        "application_fee" => StripeKO::money_format($application_fee)), 
                                                     array('stripe_account' => $order->ad->user->stripe_user_id)
                                                    );
                    }
                    else
                    {
                        $charge = \Stripe\Charge::create(array(
                                                        "amount"    => StripeKO::money_format($order->amount), // amount in cents, again
                                                        "currency"  => $order->currency,
                                                        "card"      => $token,
                                                        "description" => $order->description)
                                                    );
                    }
                    
                }
                catch(Exception $e) 
                {
                    // The card has been declined
                    Kohana::$log->add(Log::ERROR, 'Stripe The card has been declined');
                    Alert::set(Alert::ERROR, 'Stripe The card has been declined');
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                }
                
                //mark as paid
                $order->confirm_payment('stripe',Core::post('stripeToken'));
                
                //only if is not admin
                if (! $order->ad->user->is_admin())
                {
                    //crete new order for the application fee so we know how much the site owner is earning ;)
                    $order_app = Model_Order::new_order($order->ad, $order->ad->user,
                                                        Model_Order::PRODUCT_APP_FEE, $application_fee, core::config('payment.paypal_currency'),
                                                        'id_order->'.$order->id_order.' id_ad->'.$order->ad->id_ad);
                    $order_app->confirm_payment('stripe',Core::post('stripeToken'));
                }
                
                //redirect him to his ads
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'orders')));
            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
            }
            
        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
        }
    }

    /**
     * connects the loged user to his stripe account
     * code based on https://gist.github.com/7109113
     * see https://stripe.com/docs/connect/standalone-accounts
     * @return [type] [description]
     */
    public function action_connect()
    {
        // only if stripe connect enabled
        if (Core::config('payment.stripe_connect')==FALSE )
            throw HTTP_Exception::factory(404,__('Page not found'));

        //user needs to be loged in
        if (!Auth::instance()->logged_in())
            $this->redirect(Route::url('oc-panel',array('controller'=>'auth','action'=>'login')).'?auth_redirect='.URL::current());

        //stored in configs
        $client_id = Core::config('payment.stripe_clientid');

        if (isset($_GET['code'])) 
        { // Redirect w/ code
            $code = $_GET['code'];

            $token_request_body = array(
                                        'client_secret' => Core::config('payment.stripe_private'),
                                        'grant_type'    => 'authorization_code',
                                        'client_id'     => $client_id,
                                        'code'          => $code,
                                        );

            $req = curl_init('https://connect.stripe.com/oauth/token');
            curl_setopt($req, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($req, CURLOPT_POST, TRUE );
            curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
            $response = curl_exec($req);

            if( ! curl_errno($req))
            {
                curl_close($req);
                $response = json_decode($response, TRUE);
                
                if(isset($response['error_description']))
                    Alert::set(Alert::ERROR,$response['error_description']);
                elseif(isset($response['stripe_user_id']))
                {
                    //save into the user
                    $this->user->stripe_user_id = $response['stripe_user_id'];
                    $this->user->save();
                    Alert::set(Alert::INFO, __('Stripe Connected'));
                }
            }
            else 
                Alert::set(Alert::ERROR, 'We could not connect with Stripe.');     
        } 
        elseif (isset($_GET['error'])) 
            Alert::set(Alert::ERROR, $_GET['error']);
        else 
        { // redirect user to stripe connect
            $authorize_request_body = array(
                                            'response_type' => 'code',
                                            'scope'         => 'read_write',
                                            'client_id'     => $client_id
                                            );

            $url = 'https://connect.stripe.com/oauth/authorize?' . http_build_query($authorize_request_body);
            //echo "<a href='$url'>Connect with Stripe</a>";
            $this->redirect($url);
        }

        $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))); 
    }

}