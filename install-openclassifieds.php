<? 
/**
 * HTML template for the install
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

ob_start(); 
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
@set_time_limit(0);
// Set the full path to the docroot
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);


/**
 * Helper installation classses
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */


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

    /**
     * default locale/language of the install
     * @var string
     */
    public static $locale = 'en_US';

    /**
     * suggested URL with folder were to install
     * @var string
     */
    public static $url = NULL;

    /**
     * suggested folder were to install
     * @var string
     */
    public static $folder = NULL;

    /**
     * message to notify
     * @var string
     */
    public static $msg = '';

     /**
      * installation error messages here
      * @var string
      */
    public static $error_msg  = '';

    /**
     * initializes the install class and process
     * @return void
     */
    public static function initialize()
    {

        // Try to guess installation URL
        self::$url = 'http://'.$_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != '80') 
            self::$url = self::$url.':'.$_SERVER['SERVER_PORT'];

        //getting the folder, erasing the index
        self::$folder = str_replace('/index.php','', $_SERVER['SCRIPT_NAME']).'/';
        self::$url .=self::$folder;
    }

    /**
     * checks that your hosting has everything that needs to have
     * @return array 
     */
    public static function requirements()
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
                'New Installation'=>array('message'   => 'Seems Open Classifieds it is already insalled',
                                        'mandatory' => TRUE,
                                        'result'    => !file_exists('oc/config/database.php')
                                        ),
                'Write DIR'       =>array('message'   => 'Can\'t write to the current directory. Please fix this by giving the webserver user write access to the directory.',
                                        'mandatory' => TRUE,
                                        'result'    => (is_writable(DOCROOT))
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
                'Safe Mode'   =>array('message'   => '<a href="http://php.net/manual/en/features.safe-mode.php>safe_mode</a> must be disabled.',
                                        'mandatory' => TRUE,
                                        'result'    => ((bool) ini_get('safe_mode'))?FALSE:TRUE
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
     * checks from requirements if its compatible or not. Also fills the msg variable
     * @return boolean 
     */
    public static function is_compatible()
    {
        self::$msg = '';
        $compatible = TRUE;
        foreach (install::requirements() as $name => $values)
        {
            if ($values['mandatory'] == TRUE AND $values['result'] == FALSE)
                $compatible = FALSE;

            if ($values['result'] == FALSE)
                self::$msg .= $values['message'].'<br>';
        }

        return $compatible;
            
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


}

function __($msgid)
{
    return $msgid;
}

class core{

    /**
     * copies files/directories recursively
     * @param  string  $source    from
     * @param  string  $dest      to
     * @param  boolean $overwrite overwrite existing file
     * @return void             
     */
    public static function copy($source, $dest, $overwrite = false)
    { 
        //Lets just make sure our new folder is already created. Alright so its not efficient to check each time... bite me
        if(is_file($dest))
        {
            copy($source, $dest);
            return;
        }
        
        if(!is_dir($dest))
            mkdir($dest);

        $objects = scandir($source);
        foreach ($objects as $object) 
        {
            if($object != '.' && $object != '..')
            { 
                $path = $source . '/' . $object; 
                if(is_file( $path))
                { 
                    if(!is_file( $dest . '/' . $object) || $overwrite) 
                    {
                        if(!@copy( $path,  $dest . '/' . $object))
                            die('File ('.$path.') could not be copied, likely a permissions problem.'); 
                    }
                }
                elseif(is_dir( $path))
                { 
                    if(!is_dir( $dest . '/' . $object)) 
                        mkdir( $dest . '/' . $object); // make subdirectory before subdirectory is copied 

                    OC::copy($path, $dest . '/' . $object, $overwrite); //recurse! 
                }
                 
            } 
        } 
     }  

    /**
     * deletes file or directory recursevely
     * @param  string $file 
     * @return void       
     */
    public static function delete($file)
    {
        if (is_dir($file)) 
        {
            $objects = scandir($file);
            foreach ($objects as $object) 
            {
                if ($object != '.' AND $object != '..') 
                {
                    if (is_dir($file.'/'.$object)) 
                        core::delete($file.'/'.$object); 
                    else 
                        unlink($file.'/'.$object);
                }
            }
            reset($objects);
            @rmdir($file);
        }
        elseif(is_file($file))
            unlink($file);
    }

    /**
     * gets the html content from a URL
     * @param  string $url 
     * @return string      
     */
    public static function curl_get_contents($url)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_TIMEOUT,30);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE); 
        $contents = curl_exec($c);
        curl_close($c);

        return ($contents)? $contents : FALSE;
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
        return (core::post($key)!==NULL)?core::post($key):core::get($key,$default);
    }
}



//start the install setup
install::initialize();
$is_compatible = install::is_compatible();

//choosing what to display
//execute installation since they are posting data
if ( ($_POST OR isset($_GET['SITE_NAME'])) AND $is_compatible === TRUE)
    $view = (install::execute()===TRUE)?'success':'form';
//normally if its compaitble just display the form
elseif ($is_compatible === TRUE)
    $view = 'form';
//not compatible
else
    $view = 'hosting';
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en>"> <!--<![endif]-->
<head>
    <meta charset="utf8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Open Classifieds <?=__("Installation")?></title>
    <meta name="keywords" content="" >
    <meta name="description" content="" >
    <meta name="copyright" content="Open Classifieds <?=install::version?>" >
    <meta name="author" content="Open Classifieds">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="shortcut icon" href="http://open-classifieds.com/wp-content/uploads/2012/04/favicon1.ico" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>    <![endif]-->
       
    <style type="text/css">
    body {
        padding-top: 60px;
        padding-bottom: 40px;
    }

    .sidebar-nav {
        padding: 9px 0;
    }
    .chosen-single{padding: 4px 0px 27px 8px!important;}
    .chosen-single b{margin: 4px!important;}
    .navbar-brand{padding: 4px 50px 0px 0px!important;}
    .we-install{padding: 11px!important;margin-top: 7px;}
    .adv{display: none;}
    .logo img {margin-top: 10px;}
    .page-header{margin: 25px 0 21px!important;}
    .mb-10{margin-bottom: 10px!important;}
    #myTab{margin-top: 14px;}

    </style>
        
    <link href="//netdna.bootstrapcdn.com/bootswatch/3.1.0/flatly/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chosen/1.1.0/chosen.min.css">

</head>

<body>
    <div class="container">
        <div class="navbar navbar-fixed-top navbar-inverse">

            <div class="navbar-inner">
                <div class="container">
                    <button class="navbar-toggle pull-left" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-collapse bs-navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#home" data-toggle="tab">Install</a></li>
                            <li><a href="http://open-classifieds.com/support/" target="_blank">Support</a></li>
                            <li><a href="#requirements" data-toggle="tab">Requirements</a></li>
                            <li><a href="#about" data-toggle="tab">About</a></li>
                        </ul>

                        <div class="btn-group pull-right">
                            <a class="btn btn-primary we-install" href="http://open-classifieds.com/market/">
                                <i class="glyphicon-shopping-cart glyphicon"></i> <?=__("We install it for you, Buy now!")?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <a class="logo" target="_blank" href="http://open-classifieds.com">
            <img src="http://open-classifieds.com/wp-content/uploads/2012/04/OC_noTagline_286x52.png" alt="Open Classifieds <?=__("Installation")?>">
        </a>    
        <div class="tab-content">
            <div class="tab-pane fade in active" id="home">
                <?install::view($view)?>
            </div>
            <div class="tab-pane fade" id="requirements">
                <?install::view('requirements')?>
            </div>
            <div class="tab-pane fade" id="about">
                <?install::view('about')?>
            </div>
        </div>
           
        <hr>

        <footer>
            <p>
            &copy;  <a href="http://open-classifieds.com" title="Open Source PHP Classifieds">Open Classifieds</a> 2009 - <?=date('Y')?>
            </p>
        </footer>
    </div> 
    
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/jquery.bootstrapvalidation/1.3.7/jqBootstrapValidation.min.js"></script>
    <script src="//cdn.jsdelivr.net/chosen/1.1.0/chosen.jquery.min.js"></script>

    <script>
        $(function () { 
            $("select").chosen();
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); 
            $('input, select').tooltip(); 
        });

        $('#advanced-options').click(function(){
            if($(this).hasClass('btn-primary'))
            {
                $(this).removeClass('btn-primary');
                $(this).addClass('btn-default');
                $('.adv').each(function(){
                    $(this).hide();
                });
                $('#myTab').css('display','none');
            }
            else
            {
                $(this).removeClass('btn-default');
                $(this).addClass('btn-primary');
                $('.adv').each(function(){
                    $(this).show();
                });
                $('#myTab').css('display','block');  
            }
        });

        $('#phpinfobutton').click(function(){
            if($('#phpinfo').hasClass('hidden'))
            {
                $(this).removeClass('btn-primary');
                $(this).addClass('btn-default');
                $('#phpinfo').removeClass('hidden');
            }
            else
            {
                $(this).removeClass('btn-default');
                $(this).addClass('btn-primary');
                $('#phpinfo').addClass('hidden');
            }
        });

    </script>

</body>
</html>