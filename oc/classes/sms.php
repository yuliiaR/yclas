<?php

// include class vendor
require Kohana::find_file('vendor/clickatell-php-3.0.0/src/', 'Rest');
require Kohana::find_file('vendor/clickatell-php-3.0.0/src/', 'ClickatellException');

use Clickatell\Rest;
use Clickatell\ClickatellException;

class Sms  {


    public static function send($phone, $message)
    {
        if (empty(Core::config('general.sms_clickatell_api')) OR Core::config('general.sms_clickatell_api')==NULL)
            return 'Please set your Clickatell SMS API Key in the panel';

        $clickatell = new \Clickatell\Rest(Core::config('general.sms_clickatell_api'));

        // Full list of support parameters can be found at https://www.clickatell.com/developers/api-documentation/rest-api-request-parameters/
        try {
            $result = $clickatell->sendMessage(['to' => [$phone], 'content' => $message]);

            foreach ($result as $message) 
            {
                return ($message['accepted'] == TRUE)?TRUE:$message['error'];

                //var_dump($message);

                /*
                [
                    'apiMsgId'  => null|string,
                    'accepted'  => boolean,
                    'to'        => string,
                    'error'     => null|string
                ]
                */
            }

        } catch (ClickatellException $e) {
            return $e->getMessage();
            // Any API call error will be thrown and should be handled appropriately.
            // The API does not return error codes, so it's best to rely on error descriptions.
            //var_dump($e->getMessage());
        }

    }

}
