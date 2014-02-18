<?
/**
 * Helper installation classses
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

// Sanity check, install should only be checked from index.php
defined('SYSPATH') or exit('Install must be loaded from within index.php!');


/**
 * Class with install functions helper
 */
class install{
    
    /**
     * 
     * Software install settings
     * @var string
     */
    const version   = '2.1.3';
    const name      = 'Open Classifieds';
    const favicon   = 'http://open-classifieds.com/wp-content/uploads/2012/04/favicon1.ico';
    const logo      = 'http://open-classifieds.com/wp-content/uploads/2012/04/OC_noTagline_286x52.png';
    const support   = 'http://open-classifieds.com/support/';



    /**
     * default locale/language of the install
     * @var string
     */
    public static $locale = 'en_US';

    /**
     * requirements checks filled on init
     * @var array
     */
    public static $checks = NULL;

    /**
     * suggested URL and folder were to install
     * @var string
     */
    public static $url = NULL;
    public static $folder = NULL;

    /**
     * initializes the install class and process
     * @return void
     */
    public static function initialize()
    {
        //Gets language to use in the install
        self::$locale  = install::request('LANGUAGE', install::get_browser_favorite_language());
        
        
        //start translations
        install::gettext_init(self::$locale);


        // Try to guess installation URL
        self::$url = 'http://'.$_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != '80') 
            self::$url = self::$url.':'.$_SERVER['SERVER_PORT'];
        //getting the folder, erasing the index
        self::$folder = str_replace('/index.php','', $_SERVER['SCRIPT_NAME']).'/');
        self::$url .=self::$folder;



        //Software requirements
        self::$checks = self::requirements();
    }

    /**
     * checks that your hosting has everything that needs to have
     * @return array 
     */
    private static function requirements()
    {

        /**
         * mod rewrite check
         */
        if(function_exists('apache_get_modules'))
        {
            $mod_msg        = 'Install requires Apache mod_rewrite module to be installed';
            $mod_mandatory  = TRUE;
            $mod_result     = in_array('mod_rewrite',apache_get_modules());
        }
        //in case they dont use apache a nicer message
        else 
        {
            $mod_msg        = 'Can not check if mod_rewrite installed, probably everything is fine. Try to proceed with the installation anyway ;)';
            $mod_mandatory  = FALSE;
            $mod_result     = FALSE;
        }
                
                
        /**
         * all the install checks
         */
        return     array(

                'robots.txt'=>array('message'   => 'The <code>'.DOCROOT.'robots.txt</code> file is not writable.',
                                    'mandatory' => FALSE,
                                    'result'    => is_writable(DOCROOT.'robots.txt')
                                    ),
                '.htaccess' =>array('message'   => 'The <code>'.DOCROOT.'.htaccess</code> file is not writable.',
                                    'mandatory' => TRUE,
                                    'result'    => is_writable(DOCROOT.'.htaccess')
                                    ),
                'sitemap'   =>array('message'   => 'The <code>'.DOCROOT.'sitemap.xml.gz</code> file is not writable.',
                                    'mandatory' => FALSE,
                                    'result'    => is_writable(DOCROOT.'sitemap.xml.gz')
                                    ),
                'images'    =>array('message'   => 'The <code>'.DOCROOT.'images/</code> directory is not writable.',
                                    'mandatory' => TRUE,
                                    'result'    => is_writable(DOCROOT.'images')
                                    ),
                'themes'    =>array('message'   => 'The <code>'.DOCROOT.'themes/</code> directory is not writable.',
                                    'mandatory' => TRUE,
                                    'result'    => is_writable(DOCROOT.'themes')
                                    ),
                'cache'     =>array('message'   => 'The <code>'.APPPATH.'cache/</code> directory is not writable.',
                                    'mandatory' => TRUE,
                                    'result'    => (is_dir(APPPATH) AND is_dir(APPPATH.'cache') AND is_writable(APPPATH.'cache'))
                                    ),
                'logs'      =>array('message'   => 'The <code>'.APPPATH.'logs/</code> directory is not writable.',
                                    'mandatory' => TRUE,
                                    'result'    => (is_dir(APPPATH) AND is_dir(APPPATH.'logs') AND is_writable(APPPATH.'logs'))
                                    ),
                'SYS'       =>array('message'   => 'The configured <code>'.SYSPATH.'</code> directory does not exist or does not contain required files.',
                                    'mandatory' => TRUE,
                                    'result'    => (is_dir(SYSPATH) AND is_file(SYSPATH.'classes/kohana'.EXT))
                                    ),
                'APP'       =>array('message'   => 'The configured <code>'.APPPATH.'</code> directory does not exist or does not contain required files.',
                                    'mandatory' => TRUE,
                                    'result'    => (is_dir(APPPATH) AND is_file(APPPATH.'bootstrap'.EXT))
                                    ),
                'PHP'   =>array('message'   => 'PHP 5.3 or newer required, this version is '. PHP_VERSION,
                                    'mandatory' => TRUE,
                                    'result'    => version_compare(PHP_VERSION, '5.3', '>=')
                                    ),
                'mod_rewrite'=>array('message'  => $mod_msg,
                                    'mandatory' => $mod_mandatory,
                                    'result'    => $mod_result
                                    ),
                'Short Tag'   =>array('message'   => '<a href="http://www.php.net/manual/en/ini.core.php#ini.short-open-tag">short_open_tag</a> must be enabled in your php.ini.',
                                    'mandatory' => TRUE,
                                    'result'    => (bool) ini_get('short_open_tag')
                                    ),
                'PCRE UTF8' =>array('message'   => '<a href="http://php.net/pcre">PCRE</a> has not been compiled with UTF-8 support.',
                                    'mandatory' => TRUE,
                                    'result'    => (bool) (@preg_match('/^.$/u', 'ñ'))
                                    ),
                'PCRE Unicode'=>array('message' => '<a href="http://php.net/pcre">PCRE</a> has not been compiled with Unicode property support.',
                                    'mandatory' => TRUE,
                                    'result'    => (bool) (@preg_match('/^\pL$/u', 'ñ'))
                                    ),
                'SPL'       =>array('message'   => 'PHP <a href="http://www.php.net/spl">SPL</a> is either not loaded or not compiled in.',
                                    'mandatory' => TRUE,
                                    'result'    => (function_exists('spl_autoload_register'))
                                    ),
                'Reflection'=>array('message'   => 'PHP <a href="http://www.php.net/reflection">reflection</a> is either not loaded or not compiled in.',
                                    'mandatory' => TRUE,
                                    'result'    => (class_exists('ReflectionClass'))
                                    ),
                'Filters'   =>array('message'   => 'The <a href="http://www.php.net/filter">filter</a> extension is either not loaded or not compiled in.',
                                    'mandatory' => TRUE,
                                    'result'    => (function_exists('filter_list'))
                                    ),
                'Iconv'     =>array('message'   => 'The <a href="http://php.net/iconv">iconv</a> extension is not loaded.',
                                    'mandatory' => TRUE,
                                    'result'    => (extension_loaded('iconv'))
                                    ),
                'Mbstring'  =>array('message'   => 'The <a href="http://php.net/mbstring">mbstring</a> extension is not loaded.',
                                    'mandatory' => TRUE,
                                    'result'    => (extension_loaded('mbstring'))
                                    ),
                'CType'     =>array('message'   => 'The <a href="http://php.net/ctype">ctype</a> extension is not enabled.',
                                    'mandatory' => TRUE,
                                    'result'    => (function_exists('ctype_digit'))
                                    ),
                'URI'       =>array('message'   => 'Neither <code>$_SERVER[\'REQUEST_URI\']</code>, <code>$_SERVER[\'PHP_SELF\']</code>, or <code>$_SERVER[\'PATH_INFO\']</code> is available.',
                                    'mandatory' => TRUE,
                                    'result'    => (isset($_SERVER['REQUEST_URI']) OR isset($_SERVER['PHP_SELF']) OR isset($_SERVER['PATH_INFO']))
                                    ),
                'cUrl'      =>array('message'   => 'Install requires the <a href="http://php.net/curl">cURL</a> extension for the Request_Client_External class.',
                                    'mandatory' => TRUE,
                                    'result'    => (extension_loaded('curl'))
                                    ),
                'mcrypt'    =>array('message'   => 'Install requires the <a href="http://php.net/mcrypt">mcrypt</a> for the Encrypt class.',
                                    'mandatory' => TRUE,
                                    'result'    => (extension_loaded('mcrypt'))
                                    ),
                'GD'        =>array('message'   => 'Install requires the <a href="http://php.net/gd">GD</a> v2 for the Image class',
                                    'mandatory' => TRUE,
                                    'result'    => (function_exists('gd_info'))
                                    ),
                'MySQL'     =>array('message'   => 'Install requires the <a href="http://php.net/mysql">MySQL</a> extension to support MySQL databases.',
                                    'mandatory' => TRUE,
                                    'result'    => (function_exists('mysql_connect'))
                                    ),
                'ZipArchive'   =>array('message'   => 'PHP module zip not installed. You will need this to auto update the software.',
                                    'mandatory' => FALSE,
                                    'result'    => class_exists('ZipArchive')
                                    ),
                );
    }

    /**
     * includes a view file
     */
    public static function view($file)
    {
        include_once 'views/'.$file.EXT;
    }

    /**
     * get phpinfo clean in a string
     * @return strin 
     */
    public static function phpinfo()
    {
        ob_start();                                                                                                        
        @phpinfo();                                                                                                     
        $phpinfo = ob_get_contents();                                                                                         
        ob_end_clean();  
        //strip the body html                                                                                                  
        return preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
    }

    /**
     * Parse Accept-Language HTTP header to detect user's language(s) 
     * and get the most favorite one
     *
     * Adapted from
     * @link http://www.thefutureoftheweb.com/blog/use-accept-language-header
     * @param string $lang default language to retunr in case of any
     * @return NULL|string  favorite user's language
     *
     */
    private static function get_browser_favorite_language($lang = 'en_US')
    {
        if ( ! isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            return $lang;

        // break up string into pieces (languages and q factors)
        preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

        if ( ! count($lang_parse[1]))
            return $lang;

        // create a list of languages in the form 'es' => 0.8
        $langs = array_combine($lang_parse[1], $lang_parse[4]);

        // set default to 1 for languages without a specified q factor
        foreach ($langs as $lang => $q)
            if ($q === '') $langs[$lang] = 1;

        arsort($langs, SORT_NUMERIC); // sort list based on q factor. higher first
        reset($langs);
        $lang = strtolower(key($langs)); // Gotcha ! the 1st top favorite language

        // when necessary, convert 'es' to 'es_ES'
        // so scandir("languages") will match and gettext_init below can seamlessly load its stuff
        if (strlen($lang) == 2)
            $lang .= '_'.strtoupper($lang);

        return $lang;
    }

    /**
     * loads gettexts or droppin
     * @param  string $locale  
     * @param  string $domain  
     * @param  string $charset 
     */
    private static function gettext_init($locale,$domain = 'messages',$charset = 'utf8')
    {
        /**
         * check if gettext exists if not uses gettext dropin
         */
        $locale_res = setlocale(LC_ALL, $locale);
        if ( !function_exists('_') OR $locale_res===FALSE OR empty($locale_res) )
        {
            /**
             * gettext override
             * v 1.0.11
             * https://launchpad.net/php-gettext/
             * We load php-gettext here since Kohana_I18n tries to create the function __() function when we extend it.
             * PHP-gettext already does this.
             */
            include APPPATH.'vendor/php-gettext/gettext.inc';
            
            T_setlocale(LC_ALL, $locale);
            T_bindtextdomain($domain,DOCROOT.'languages');
            T_bind_textdomain_codeset($domain, $charset);
            T_textdomain($domain);
        }
        /**
         * gettext exists using fallback in case locale doesn't exists
         */
        else
        {
            bindtextdomain($domain,DOCROOT.'languages');
            bind_textdomain_codeset($domain, $charset);
            textdomain($domain);

            function __($msgid)
            {
                return _($msgid);
            }
        }

    }

    /**
     * gets the offset of a date
     * @param  string $offset 
     * @return string       
     */
    private static function format_offset($offset) 
    {
            $hours = $offset / 3600;
            $remainder = $offset % 3600;
            $sign = $hours > 0 ? '+' : '-';
            $hour = (int) abs($hours);
            $minutes = (int) abs($remainder / 60);

            if ($hour == 0 AND $minutes == 0) {
                $sign = ' ';
            }
            return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) .':'. str_pad($minutes,2, '0');
    }


    /**
     * returns timezones ins a more friendly array way, ex Madrid [+1:00]
     * @return array 
     */
    private static function get_timezones()
    {
        if (method_exists('DateTimeZone','listIdentifiers'))
        {
            $utc = new DateTimeZone('UTC');
            $dt  = new DateTime('now', $utc);

            $timezones = array();
            $timezone_identifiers = DateTimeZone::listIdentifiers();

            foreach( $timezone_identifiers as $value )
            {
                $current_tz = new DateTimeZone($value);
                $offset     =  $current_tz->getOffset($dt);

                if ( preg_match( '/^(America|Antartica|Africa|Arctic|Asia|Atlantic|Australia|Europe|Indian|Pacific)\//', $value ) )
                {
                    $ex=explode('/',$value);//obtain continent,city
                    $city = isset($ex[2])? $ex[1].' - '.$ex[2]:$ex[1];//in case a timezone has more than one
                    $timezones[$ex[0]][$value] = $city.' ['.install::format_offset($offset).']';
                }
            }
            return $timezones;
        }
        else//old php version
        {
            return FALSE;
        }
    }

    /**
     * return HTML select for the timezones
     * @param  string $select_name 
     * @param  string $selected    
     * @return string              
     */
    public static function get_select_timezones($select_name='TIMEZONE', $selected=NULL)
    {
        if ($selected=='UTC') 
            $selected='Europe/London';

        $timezones = install::get_timezones();
        $sel = '<select class="form-control" id="'.$select_name.'" name="'.$select_name.'">';
        foreach( $timezones as $continent=>$timezone )
        {
            $sel.= '<optgroup label="'.$continent.'">';
            foreach( $timezone as $city=>$cityname )
            {            
                $seloption = ($city==$selected) ? ' selected="selected"' : '';
                $sel .= "<option value=\"$city\"$seloption>$cityname</option>";
            }
            $sel.= '</optgroup>';
        }
        $sel.='</select>';

        return $sel;
    }

    /**
     * replaces in a file 
     * @param  string $orig_file 
     * @param  array $search   
     * @param  array $replace  
     * @return bool           
     */
    public static function replace_file($orig_file,$search, $replace,$to_file = NULL)
    {
        if ($to_file === NULL)
            $to_file = $orig_file;

        //check file is writable
        if (is_writable($to_file))
        {
            //read file content
            $content = file_get_contents($orig_file);
            //replace fields
            $content = str_replace($search, $replace, $content);
            //save file
            return install::write_file($to_file,$content);
        }
        
        return FALSE;
    }


    /**
     * write to file
     * @param $filename fullpath file name
     * @param $content
     * @return boolean
     */
    public static function write_file($filename,$content)
    {
        $file = fopen($filename, 'w');
        if ($file)
        {//able to create the file
            fwrite($file, $content);
            fclose($file);
            return TRUE;
        }
        return FALSE;   
    }

    /**
     * generates passwords, used for the auth hash keys etc..
     * @param  integer $length 
     * @return string         
     */
    public static function generate_password ($length = 16)
    {
        $password = '';
        // define possible characters
        $possible = '0123456789abcdefghijklmnopqrstuvwxyz_-';

        // add random characters to $password until $length is reached
        for ($i=0; $i <$length ; $i++) 
        { 
            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            $password .= $char;
        }

        return $password;
    }

    /**
     * cleans an string of spaces etc
     * @param  string $s 
     * @return string    clean
     */
    public static function slug($s) 
    {
        // everything to lower and no spaces begin or end
        $s = strip_tags(strtolower(trim($s)));
     
        // adding - for spaces and union characters
        $find = array(' ', '&', '+', '-',',','.',';');
        $s = str_replace ($find, '', $s);

        //return the friendly s
        return $s;
    }

    /**
     * shortcut for the query method $_GET
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function get($key,$default=NULL)
    {
        return (isset($_GET[$key]))?$_GET[$key]:$default;
    }

    /**
     * shortcut for $_POST[]
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function post($key,$default=NULL)
    {
        return (isset($_POST[$key]))?$_POST[$key]:$default;
    }

    /**
     * shortcut to get or post
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function request($key,$default=NULL)
    {
        return (install::post($key)!==NULL)?install::post($key):install::get($key,$default);
    }


    /**
     * installs the software
     * @return [type] [description]
     */
    public static function install()
    {
        $install    = TRUE;
    
        ///////////////////////////////////////////////////////
            //check DB connection
            $link = @mysql_connect(isntall::request('DB_HOST'), isntall::request('DB_USER'), isntall::request('DB_PASS'));
            if (!$link) 
            {
                $error_msg = __('Cannot connect to server').' '. isntall::request('DB_HOST').' '. mysql_error();
                $install = FALSE;
            }
            
            if ($link && $install) 
            {
                if (isntall::request('DB_NAME'))
                {
                    $dbcheck = @mysql_select_db(isntall::request('DB_NAME'));
                    if (!$dbcheck)
                    {
                         $error_msg.= __('Database name').': ' . mysql_error();
                         $install = FALSE;
                    }
                }
                else 
                {
                    $error_msg.= '<p>'.__('No database name was given').'. '.__('Available databases').':</p>';
                    $db_list = @mysql_query('SHOW DATABASES');
                    $error_msg.= '<pre>';
                    if (!$db_list) 
                    {
                        $error_msg.= __('Invalid query'). ':<br>' . mysql_error();
                    }
                    else 
                    {
                        while ($row = mysql_fetch_assoc($db_list)) 
                        {
                            $error_msg.= $row['Database') . '<br>';
                        }
                    }

                    $error_msg.= '</pre>';
                    $install    = FALSE;
                }
            }

            //save DB config/database.php
            if ($install)
            {
                //clean prefix
                isntall::request('TABLE_PREFIX') = slug(isntall::request('TABLE_PREFIX'));
                $search  = array('[DB_HOST]', '[DB_USER]','[DB_PASS]','[DB_NAME]','[TABLE_PREFIX]','[DB_CHARSET]');
                $replace = array(isntall::request('DB_HOST'), isntall::request('DB_USER'), isntall::request('DB_PASS'),isntall::request('DB_NAME'),isntall::request('TABLE_PREFIX'),isntall::request('DB_CHARSET'));
                $install = install::replace_file(DOCROOT.'isntall2/samples/database.php',$search,$replace,APPPATH.'config/database.php');
                if (!$install)
                    $error_msg = __('Problem saving '.APPPATH.'config/database.php');
            }

            
            //install DB
            if ($install)
            {
                //check if has key is posted if not generate
                $hash_key = ((core::request('HASH_KEY')!='')?core::request('HASH_KEY'): generate_password() );
               
                //check if DB was already installed, I use content since is the last table to be created
                if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".isntall::request('TABLE_PREFIX')."content'"))==1) 
                    $installed = TRUE;
                else
                    $installed = FALSE;

                if ($installed===FALSE)//if was installed do not launch the SQL. 
                    include DOCROOT.'isntall2/install.sql.php';
            }

        ///////////////////////////////////////////////////////
            //AUTH config
            if ($install)
            {
                $search  = array('[HASH_KEY]', '[COOKIE_SALT]','[QL_KEY]');
                $replace = array($hash_key,$hash_key,$hash_key);
                $install = install::replace_file(DOCROOT.'isntall2/samples/auth.php',$search,$replace,APPPATH.'config/auth.php');
                if (!$install)
                    $error_msg = __('Problem saving '.APPPATH.'config/auth.php');
            }

        ///////////////////////////////////////////////////////
            //create robots.txt
            if ($install)
            {
                $search  = array('[SITE_URL]', '[SITE_FOLDER]');
                $replace = array(isntall::request('SITE_URL'), self::$folder);
                $install = install::replace_file(DOCROOT.'isntall2/samples/robots.txt',$search,$replace,DOCROOT.'robots.txt');
                if (!$install)
                    $error_msg = __('Problem saving '.DOCROOT.'robots.txt');
            }


        ///////////////////////////////////////////////////////
            //create htaccess
            if ($install)
            {
                $search  = array('[SITE_FOLDER]');
                $replace = array(self::$folder);

                $install = install::replace_file(DOCROOT.'isntall2/samples/example.htaccess',$search,$replace,DOCROOT.'.htaccess');

                if (!$install)
                    $error_msg = __('Problem saving '.DOCROOT.'.htaccess');
            }


        ///////////////////////////////////////////////////////
            //ocaku register
            if ($install AND core::request('OCAKU') !== NULL)
            {
                //ocaku register new site!
                $ocaku = new ocaku();
                $ocaku->newSite(array(
                                    'siteName'=>isntall::request('SITE_NAME'),
                                    'siteUrl' =>isntall::request('SITE_URL'),
                                    'email'   =>isntall::request('ADMIN_EMAIL'),
                                    'language'=>substr(isntall::request('LANGUAGE'),0,2)
                ));
                
            }

        ///////////////////////////////////////////////////////
            //all good!
            if ($install) 
                unlink(DOCROOT.'install2/install.lock');//prevents from performing a new install
            //else @todo mysql rollback??
    
    }
}



/*
 * Name:    ocaku API
 * URL:     http://ocacu.com
 * Version: 0.3
 * Date:    18/03/2013
 * Author:  Chema Garrido
 * License: GPL v3
 * Notes:   API Class for Ocaku.com
 */
class ocaku{
    private $returnReq=false;//returns the output for the request
    private $apiUrl='http://ocacu.com/api/';//url for the requests
    private $timeout=10;//timeout for the request
    
    function __construct($return=false){
        $this->returnReq=$return;
    }
    
    public function newSite($data){
        return json_decode($this->sendRequest("newSite",$data,true));
    }
    
    //sends the request to the server, uses curl
    private function sendRequest($action,$data,$return=false){
        $ch = curl_init();
        if ($ch) {
            $data=$this->generateArrayParam($data);//var_dump($data);
            curl_setopt($ch, CURLOPT_URL,$this->apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"&action=$action".$data);
            curl_setopt($ch, CURLOPT_TIMEOUT,$this->timeout); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output=curl_exec ($ch);
            curl_close ($ch); 
            if ($return) return $server_output;
        }
        else return false;
    }
    //end send request

    //Generate array parameter
    private function generateArrayParam($values){
        $commandstring = '';
        if (is_array($values)) { 
            foreach ($values as $key => $value) {
                  $commandstring .= '&'.$key."=".$value;
            }
        } 
        else  $commandstring = $values;//not array    
        return $commandstring;
    }
    //end Generate array parameter
}