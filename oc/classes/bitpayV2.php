<?php defined('SYSPATH') or die('No direct script access.');

/**
 * bitpay helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class BitpayV2
{
    /**
     * generates HTML for apy buton
     * @param  Model_Order $order
     * @return string
     */
    public static function button(Model_Order $order)
    {
        if (Core::config('payment.bitpay_token') != '' AND Theme::get('premium') == 1 AND
            Auth::instance()->logged_in() AND $order->loaded()) {

            require_once Kohana::find_file('vendor', 'bitpay/vendor/autoload', 'php');

            $private_key = unserialize(Core::config('payment.bitpay_private_key'));
            $public_key = unserialize(Core::config('payment.bitpay_public_key'));
            $client = new \Bitpay\Client\Client();
            $network = new \Bitpay\Network\Livenet();
            if (Core::config('payment.bitpay_sandbox') == 1)
                $network = new \Bitpay\Network\Testnet();
            $adapter = new \Bitpay\Client\Adapter\CurlAdapter();

            $client->setPrivateKey($private_key);
            $client->setPublicKey($public_key);
            $client->setNetwork($network);
            $client->setAdapter($adapter);

            $token = new \Bitpay\Token();
            $token->setToken(Core::config('payment.bitpay_token'));

            $client->setToken($token);

            $invoice = new \Bitpay\Invoice();
            $buyer = new \Bitpay\Buyer();
            $buyer_email = $order->user->email;
            $buyer->setEmail($buyer_email);

            $invoice->setBuyer($buyer);

            $item = new \Bitpay\Item();
            $item->setCode($order->ad->id_ad)
                ->setDescription(Text::limit_chars(Text::removebbcode($order->description), 30, NULL, TRUE))
                ->setPrice($order->amount);
            $invoice->setItem($item);

            $invoice->setCurrency(new \Bitpay\Currency($order->currency));

            $invoice->setOrderId($order->id_order)
                ->setNotificationUrl(Route::url('default', array('controller' => 'bitpayv2', 'action' => 'ipn', 'id' => $order->id_order)));

            try {
                $client->createInvoice($invoice);
            } catch (\Exception $e) {
                $request = $client->getRequest();
                $response = $client->getResponse();
                echo (string)$request . PHP_EOL . PHP_EOL . PHP_EOL;
                echo (string)$response . PHP_EOL . PHP_EOL;
                exit(1);
            }

            return View::factory('pages/bitpay/button_v2', ['invoice' => $invoice]);
        }

        return '';
    }
}
