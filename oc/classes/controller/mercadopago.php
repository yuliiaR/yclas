<?php defined('SYSPATH') or die('No direct script access.');

/**
 * MercadoPago class
 *
 * @package Open Classifieds
 * @subpackage Core
 * @category Payment
 * @author Chema Garrido <chema@open-classifieds.com>
 * @license GPL v3
 */

class Controller_MercadoPago extends Controller{
	

	public function after()
	{

	}
	
	public function action_ipn()
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
            // Include Mercadopago library
            require Kohana::find_file('vendor/mercadopago', 'mercadopago');

            // Create an instance with your MercadoPago credentials (CLIENT_ID and CLIENT_SECRET):
            $mp = new MP(core::config('payment.mercadopago_client_id'), core::config('payment.mercadopago_client_secret'));

            $params = ["access_token" => $mp->get_access_token()];

            // Check mandatory parameters
            if (!isset(Core::get('id'), Core::get('topic') || !ctype_digit(Core::get('id'))) 
            {
                $this->response->body('KO');
            }

            // Get the payment reported by the IPN. Glossary of attributes response in https://developers.mercadopago.com
            if(Core::get('topic') == 'payment')
            {
                $payment_info = $mp->get("/collections/notifications/" . Core::get('id'), $params, false);
                $merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"], $params, false);
                // Get the merchant_order reported by the IPN. Glossary of attributes response in https://developers.mercadopago.com    
            }
            else if(Core::get('topic') == 'merchant_order')
            {
                $merchant_order_info = $mp->get("/merchant_orders/" . Core::get('id'), $params, false);
            }

            //If the payment's transaction amount is equal (or bigger) than the merchant order's amount you can release your items 
            if ($merchant_order_info["status"] == 200) 
            {
                $transaction_amount_payments= 0;
                $transaction_amount_order = $merchant_order_info["response"]["total_amount"];
                $payments=$merchant_order_info["response"]["payments"];

                foreach ($payments as  $payment) 
                {
                    if($payment['status'] == 'approved')
                    {
                        $transaction_amount_payments += $payment['transaction_amount'];
                    }   
                }

                //correct payment
                if($transaction_amount_payments >= $transaction_amount_order)
                {
                    $order->confirm_payment('mercadopago',Core::get('id')); 
                    $this->response->body('OK');
                }
                else
                {
                    Kohana::$log->add(Log::ERROR, 'A payment has been made but is flagged as INVALID');
                    $this->response->body('KO');
                }
            }
        }
        else
        {
            Kohana::$log->add(Log::ERROR, 'Order not loaded');
            $this->response->body('KO');
        }


	} 

}