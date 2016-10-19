<?php

/**
 * Stripe helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class StripeKO extends OC_StripeKO {

    public static function init()
    {
        // include class vendor
        require Kohana::find_file('vendor/stripe', 'init');

        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here https://manage.stripe.com/account
        \Stripe\Stripe::setAppInfo('Open Classifieds', Core::VERSION, 'http://open-classifieds.com');
        \Stripe\Stripe::setApiKey(Core::config('payment.stripe_private'));
    }
}