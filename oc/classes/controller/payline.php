<?php defined('SYSPATH') or die('No direct script access.');

/**
 * payline class
 *
 * @package Open Classifieds
 * @subpackage Core
 * @category Payment
 * @author Oliver <oliver@open-classifieds.com>
 * @license GPL v3
 */

class Controller_Payline extends Controller{

    public function action_result()
    {
        $this->auto_render = FALSE;

        $order = new Model_Order($this->request->param('id'));

        if ($order->loaded())
        {
            //its a fraud...lets let him know
            if ( $order->is_fraud() === TRUE )
            {
                Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
            }

            //correct payment?
            if ( ($result = payline::check_result($order)) !== FALSE)
            {
                //mark as paid
                $order->confirm_payment('payline', Core::request('token'));

                //redirect him to his ads
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                $this->redirect(Route::url('oc-panel', ['controller' => 'profile', 'action' => 'orders']));
            }
            else
            {
                Alert::set(Alert::INFO, __('Transaction not successful!'));
                $this->redirect(Route::url('default', ['controller' => 'ad', 'action' => 'checkout', 'id' => $order->id_order]));
            }
        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
        }
    }

}
