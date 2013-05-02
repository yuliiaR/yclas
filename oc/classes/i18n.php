<?php defined('SYSPATH') or die('No direct script access.');
/**
* I18n class for php-gettext
*
* @package    I18n
* @category   Translations
* @author     Chema <chema@garridodiaz.com>
* @copyright  (c) 2009-2013 Open Classifieds Team
* @license    GPL v3
*/

//Initialization

/**
 * gettext override
 * v 1.0.11
 * https://launchpad.net/php-gettext/
 * We load php-gettext here since Kohana_I18n tries to create the function __() function when we extend it.
 * PHP-gettext already does this.
 */
require Kohana::find_file('vendor', 'php-gettext/gettext','inc');


class I18n extends Kohana_I18n {

    public static $locale;
    public static $charset;
    public static $domain;
    


    /**
     * 
     * Initializes the php-gettext
     * Remember to load first php-gettext
     * @param string $locale
     * @param string $charset
     * @param string $domain
     */
    public static function initialize($locale = 'en_EN', $charset = 'utf-8', $domain = 'messages')
    {        	
        /**
         * setting the statics so later we can access them from anywhere
         */
        self::$locale  = $locale;
        self::$charset = $charset;
        self::$domain  = $domain;
        
        //time zone set in the config
        date_default_timezone_set(Kohana::$config->load('i18n')->timezone);
        
        //Kohana core charset, used in the HTML templates as well
        Kohana::$charset  = self::$charset;
                
        /**
         * check if gettext exists if not uses gettext dropin
         */
        if ( !function_exists('_') )
        {
            T_setlocale(LC_MESSAGES, self::$locale);
            bindtextdomain(self::$domain,DOCROOT.'languages');
            bind_textdomain_codeset(self::$domain, self::$charset);
            textdomain(self::$domain);
        }
        /**
         * gettext exists using fallback in case locale doesn't exists
         */
        else
        {
            T_setlocale(LC_MESSAGES, self::$locale);
            T_bindtextdomain(self::$domain,DOCROOT.'languages');
            T_bind_textdomain_codeset(self::$domain, self::$charset);
            T_textdomain(self::$domain);
        }
        
    }    

    /**
     * get the language used in the HTML
     * @return string 
     */
    public static function html_lang()
    {
        return substr(core::config('i18n.locale'),0,2);
    }
    
    /**
     * 
     * Override normal translate
     * @param string $string to translate
     * @param string $lang does nothing, legacy
     */
    public static function get($string, $lang = NULL)
    {
        return __($string);
    }

    // public static function get_money_currency()
    // {
    //     // $currencies = array('dollar'=>array('locale'=>array('en_US'), 'sign'=>'$'),
    //     //                     'pound'=>array('locale'=>array('en_GB'), '$pound;'),
    //     //                     'euro'=>array('locale'=>array('')),'sign'=>'$euro;'),
    //     //                     ''=>'',
    //     //                     ''=>'',
    //     //                     ''=>'',);
    // }
    
}//end i18n


/**
 *
 * echo for the translation
 * @param string $string
 * @return string
 */
function _e($string)
{
    echo T_($string);
}