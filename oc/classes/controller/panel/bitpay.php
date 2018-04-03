<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller SETTINGS contains all basic configurations displayed to Admin.
 */


class Controller_Panel_Bitpay extends Auth_Controller {

    public function action_generate_keys()
    {
        require_once Kohana::find_file('vendor', 'bitpay/vendor/autoload', 'php');

        $private_key = new \Bitpay\PrivateKey();
        $private_key->generate();

        $public_key = new \Bitpay\PublicKey();
        $public_key->setPrivateKey($private_key);
        $public_key->generate();

        Model_Config::set_value('payment', 'bitpay_private_key', serialize($private_key));
        Model_Config::set_value('payment', 'bitpay_public_key', serialize($public_key));

        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'settings', 'action'=>'payment')));
    }

    public function action_pair()
    {
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

        $pairing_code = core::request('code');

        Model_Config::set_value('payment', 'bitpay_pairing_code', $pairing_code);

        $sin = \Bitpay\SinKey::create()->setPublicKey($public_key)->generate();

        try {
            $token = $client->createToken(
                [
                    'pairingCode' => $pairing_code,
                    'label' => 'Yclas',
                    'id' => (string) $sin,
                ]
            );
        } catch (\Exception $e) {
            Alert::set(Alert::WARNING, $e->getMessage());
            Alert::set(Alert::WARNING, 'Pairing failed. Please check whether you are trying to pair a production pairing code on test.');
            HTTP::redirect(Route::url('oc-panel', array('controller' => 'settings', 'action' => 'payment')));
        }

        Model_Config::set_value('payment', 'bitpay_token', $token->getToken());

        HTTP::redirect(Route::url('oc-panel', array('controller' => 'settings', 'action' => 'payment')));
    }

}//end of controller
