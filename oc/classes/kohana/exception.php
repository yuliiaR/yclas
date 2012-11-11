<?php defined('SYSPATH') or die('No direct script access.');

/**
* Custom exception handler for typical 404/500 error
*
* @package    OC
* @category   Exception
* @author     Lysender && Chema <chema@garridodiaz.com>
* @copyright  (c) 2009-2011 Open Classifieds Team
* @license    GPL v3
*/


class Kohana_Exception extends Kohana_Kohana_Exception
{
    public static function handler(Exception $e)
    {
        // Throw errors when in development mode
        if (Kohana::$environment === Kohana::DEVELOPMENT)
        {
            parent::handler($e);
        }
        else
        {
            Kohana::$log->add(Log::ERROR, Kohana_Exception::text($e));
 
            $attributes = array(
                'action'    => 500,
                'origuri'   => rawurlencode(Arr::get($_SERVER, 'REQUEST_URI')),
                'message'   => rawurlencode($e->getMessage())
            );
 
            if ($e instanceof Http_Exception)
            {
                $attributes['action'] = $e->getCode();
            }
 
            // Error sub request
            echo Request::factory(Route::get('error')->uri($attributes))
                ->execute()
                ->send_headers()
                ->body();
        }
    }
}