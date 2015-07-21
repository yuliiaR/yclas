<?php 
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

//we check first short tags if not we can not even load the installer
if (! ((bool) ini_get('short_open_tag')) )
    die('<strong><u>OC Installation requirement</u></strong>: Before you proceed with your OC installation: Keep in mind OC uses the short tag "short cut" syntax.<br><br> Thus the <a href="http://php.net/manual/ini.core.php#ini.short-open-tag" target="_blank">short_open_tag</a> directive must be enabled in your php.ini.<br><br><u>Easy Solution</u>:<ol><li>Open php.ini file and look for line short_open_tag = Off</li><li>Replace it with short_open_tag = On</li><li>Restart then your PHP server</li><li>Refresh this page to resume your OC installation</li><li>Enjoy OC ;)</li></ol>');

//prevent from new install to be done over current existing one
if (file_exists(DOCROOT.'oc/config/database.php'))
    die('It seems Open Classifieds is already installed');


//read from oc/versions.json on CDN
$versions       = install::versions();
$last_version   = key($versions);
$is_compatible  = install::is_compatible();


/**
 * Helper installation classes
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
    const VERSION   = '2.5.0';

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
     * checks that your hosting has everything that needs to have
     * @return array 
     */
    public static function requirements()
    {

        /**
         * mod rewrite check
         */
        $mod_result = ((function_exists('apache_get_modules') AND in_array('mod_rewrite',apache_get_modules()))
            OR (getenv('HTTP_MOD_REWRITE')=='On')
            OR (strpos(@shell_exec('/usr/local/apache/bin/apachectl -l'), 'mod_rewrite') !== FALSE)
            OR (isset($_SERVER['IIS_UrlRewriteModule'])));
        $mod_msg = ($mod_result)?NULL:'Can not check if mod_rewrite is installed, probably everything is fine. Try to proceed with the installation anyway ;)';
                
                
        /**
         * all the install checks
         */
        return     array(
                'New Installation'=>array('message'   => 'It seems that Open Classifieds is already installed',
                                        'mandatory' => TRUE,
                                        'result'    => !file_exists('oc/config/database.php')
                                        ),
                'Write DIR'       =>array('message'   => 'Can\'t write to the current directory. Please fix this by giving the webserver user write access to the directory.',
                                        'mandatory' => TRUE,
                                        'result'    => (is_writable(DOCROOT))
                                        ),
                'PHP'   =>array('message'   => 'PHP 5.5 or newer is required, this version is '. PHP_VERSION,
                                    'mandatory' => TRUE,
                                    'result'    => version_compare(PHP_VERSION, '5.5', '>=')
                                    ),
                'mod_rewrite'=>array('message'  => $mod_msg,
                                    'mandatory' => FALSE,
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
                'MySQL'     =>array('message'   => 'Install requires the <a href="http://php.net/mysqli">MySQLi</a> extension to support MySQL databases.',
                                    'mandatory' => TRUE,
                                    'result'    => (function_exists('mysqli_connect'))
                                    ),
                'ZipArchive'   =>array('message'   => 'PHP module zip not installed. You will need this to auto update the software.',
                                    'mandatory' => FALSE,
                                    'result'    => class_exists('ZipArchive')
                                    ),
                'SoapClient'   =>array('message'   => 'Install requires the <a href="http://php.net/manual/en/class.soapclient.php">SoapClient</a> class.',
                                    'mandatory' => FALSE,
                                    'result'    => class_exists('SoapClient')
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
                self::$msg[] = $values['message'];
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

    /**
     * returns array of last versions from json
     * @return array
     */
    public static function versions()
    {
        return json_decode(core::curl_get_contents('http://open-classifieds.com/files/versions.json?r='.time()),TRUE);
    }


}

class core{

    /**
     * copies files/directories recursively
     * @param  string  $source    from
     * @param  string  $dest      to
     * @param  integer $overwrite 0=do not overwrite 1=force overwrite 2=overwrite only is size is different
     * @return void             
     */
    public static function copy($source, $dest, $overwrite = 0)
    { 
        //be sure source exists..
        if (!is_readable($source))
            die('File ('.$source.') could not be readed, likely a permissions problem.');

        //just a file to copy, so do it!
        if(is_file($source))
        {
            $copy_file = FALSE;

            //if doesnt exists OR we want to overwrite always OR different size copy the file.
            if( !is_file( $dest ) OR $overwrite == 1 OR ( $overwrite == 2 AND filesize($source)===filesize($dest) ) ) 
                $copy_file = TRUE;

            if ($copy_file === TRUE)
            {
                try {
                    copy($source, $dest);
                } catch (Exception $e) {
                    die('File ('.$source.') could not be copied, likely a permissions problem.');
                }     
            }
            
            //always return if its a file, so we dont move forward
            return;
        }
        
        //was not a file, so folder...lets check exists, if not create it
        if(!is_dir($dest))
            mkdir($dest);     

        //read folder contents
        $objects = scandir($source);
        foreach ($objects as $object) 
        {
            if($object != '.' && $object != '..')
            { 
                $from = $source . '/' . $object; 
                $to   = $dest   . '/' . $object;
                Core::copy($from, $to, $overwrite);                  
            } 
        } 
        
    }

    /**
     * recursively deletes file or directory
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
                        core::delete($file.'/'.$object); //recursive
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
     * @return string on success, false on errors
     */
    public static function curl_get_contents($url)
    {
        $c = curl_init(); if ($c === FALSE) return FALSE;
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_TIMEOUT,30); 
        curl_setopt($c, CURLOPT_NOPROGRESS, false);
        curl_setopt($c, CURLOPT_PROGRESSFUNCTION, array(new core, 'progressCallback'));
        // curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);
        // $contents = curl_exec($c);
        $contents = core::curl_exec_follow($c);
        curl_close($c);

        return ($contents)? $contents : FALSE;
    }

    /**
     * [curl_exec_follow description] http://us2.php.net/manual/en/function.curl-setopt.php#102121
     * @param  curl  $ch          handler
     * @param  integer $maxredirect hoe many redirects we allow
     * @return contents
     */
    public static function curl_exec_follow($ch, $maxredirect = 5) 
    { 
        //using normal curl redirect
        if (ini_get('open_basedir') == '' AND ini_get('safe_mode' == 'Off')) 
        { 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $maxredirect > 0); 
            curl_setopt($ch, CURLOPT_MAXREDIRS, $maxredirect); 
        } 
        //using safemode...WTF!
        else 
        { 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE); 
            if ($maxredirect > 0) 
            { 
                $newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); 

                $rch = curl_copy_handle($ch); 
                curl_setopt($rch, CURLOPT_HEADER, TRUE); 
                curl_setopt($rch, CURLOPT_NOBODY, TRUE); 
                curl_setopt($rch, CURLOPT_FORBID_REUSE, FALSE); 
                curl_setopt($rch, CURLOPT_RETURNTRANSFER, TRUE); 

                do 
                { 
                    curl_setopt($rch, CURLOPT_URL, $newurl); 
                    $header = curl_exec($rch); 
                    if (curl_errno($rch))
                        $code = 0; 
                    else 
                    { 
                        $code = curl_getinfo($rch, CURLINFO_HTTP_CODE); 
                        if ($code == 301 OR $code == 302) 
                        { 
                            preg_match('/Location:(.*?)\n/', $header, $matches); 
                            $newurl = trim(array_pop($matches)); 
                        }
                        else 
                            $code = 0; 
                    } 
                } 
                while ($code AND --$maxredirect); 

                curl_close($rch); 

                if (!$maxredirect) 
                { 
                    if ($maxredirect === NULL) 
                        trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING); 
                    else  
                        $maxredirect = 0; 

                    return FALSE; 
                } 

                curl_setopt($ch, CURLOPT_URL, $newurl); 
            } 
        } 

        return curl_exec($ch); 
    } 

    /**
     * rss reader
     * @param  string $url 
     * @return array      
     */
    public static function rss($url)
    {
        $rss = @simplexml_load_file($url);
         if($rss == FALSE OR ! isset($rss->channel->item))
            return array();

        return $rss->channel->item;
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
    
    public static function progressCallback($resource, $download_size, $downloaded, $upload_size, $uploaded )
    {
        static $previousProgress = 0;
        
        if ($download_size == 0)
            $progress = 0;
        else
            $progress = round($downloaded * 100 / $download_size);
        
        if ($progress > $previousProgress)
        {
            $previousProgress = $progress;
            $fp = fopen('progress.json', 'wa+');
            fputs($fp, json_encode(array('progress' => array($progress))));
            fclose($fp);
        }
    }
}

/**
 * gettext short cut currently @TODO just echoes untranslated string
 * @param  [type] $msgid [description]
 * @return [type]        [description]
 */
function __($msgid)
{
    return $msgid;
}
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
    <meta name="copyright" content="Open Classifieds <?=install::VERSION?>" >
    <meta name="author" content="Open Classifieds">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="shortcut icon" href="http://open-classifieds.com/wp-content/uploads/2012/04/favicon1.ico" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>    <![endif]-->
    
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/animatecss/3.3.0/animate.min.css" rel="stylesheet">
    
    <style type="text/css">
    body {
        background-color: #f3f3f4;
        padding-top: 80px;
        padding-bottom: 15px;
        color: #676a6c;
    }
    a {
        color: #0072A6;
    }
    a:hover {
        color: #00587F;
    }
    .logo {
        height: 45px;
    }
    .off-canvas {
        background-color: #ffffff;
        padding: 5px 20px 20px;
        -webkit-border-radius: 4px;
           -moz-border-radius: 4px;
                border-radius: 4px;
        -webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
           -moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
                box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
        -webkit-transition: max-height 0.8s;
           -moz-transition: max-height 0.8s;
                transition: max-height 0.8s;
    }
    .off-canvas h3 {
        margin-top: 0;
        font-size: 13px;
        text-align: center;
        text-transform: uppercase;
        font-weight: 400;
        padding: 15px 0;
        letter-spacing: 2px;
        border-bottom: 1px solid #EDEDEE;
        margin-bottom: 15px;
    }
    .copyright {
        font-size: 12px;
    }
    .copyright a {
        color: #676a6c;
    }
    .btn-default {
        background-color: #41bb19;
        border-color: #41bb19;
        color: #FFFFFF;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .btn-default:hover,
    .btn-default:focus,
    .btn-default.focus,
    .btn-default:active,
    .btn-default.active,
    .open > .dropdown-toggle.btn-default {
        color: #FFFFFF;
        background-color: #32AF0A;
        border-color: #32AF0A;
    }
    .btn-default.disabled,
    .btn-default.disabled.active,
    .btn-default.disabled.focus,
    .btn-default.disabled:active,
    .btn-default.disabled:focus,
    .btn-default.disabled:hover,
    .btn-default[disabled],
    .btn-default.active[disabled],
    .btn-default.focus[disabled],
    .btn-default[disabled]:active,
    .btn-default[disabled]:focus,
    .btn-default[disabled]:hover {
        background-color: #32AF0A;
        border-color: #32AF0A;
    }
    .btn.active.focus,
    .btn.active:focus,
    .btn.focus,
    .btn:active.focus,
    .btn:active:focus,
    .btn:focus {
        outline: 0;
    }
    .text-success {
        color: #41bb19;
    }
    .btn-default.action {
        letter-spacing: 0px;
    }
    .list-requirements {
        margin-bottom: 10px;
        font-size: 12px;
        line-height: 25px;
        padding-bottom: 10px;
        -webkit-column-count: 2;
           -moz-column-count: 2;
                column-count: 2;
        -webkit-column-gap: 15px;
           -moz-column-gap: 15px;
                column-gap: 15px;
    }
    .list-requirements .check {
        vertical-align: 4%;
    }
    .list-requirements .check .fa-spinner {
        width: 20px;
    }
    .alert {
        background-color: #FFFFFF;
        color: #676a6c;
        border-width: 0;
        -webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
           -moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
                box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
    }
    .alert-success {
        background-color: rgba(39, 194, 76, 0.15);
        color: #676a6c;
    }
    .alert-info {
        background-color: rgba(35, 183, 229, 0.15);
        color: #676a6c;
    }
    .alert-warning {
        background-color: rgba(255, 144, 43, 0.15);
        color: #676a6c;
    }
    .alert-danger {
        background-color: rgba(240, 80, 80, 0.15);
        color: #676a6c;
    }
    .alert-services {
        background-color: #41BB19;
        color: #ffffff;
        padding-top: 7px;
        padding-bottom: 7px;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 12px;
    }
    .alert-services:hover {
        background-color: #32AF0A;
    }
    .alert-services a {
        color: #FFFFFF;
    }
    .alert-services a:hover {
        text-decoration: none;
    }
    .alerts {
        margin-top: -60px;
    }

    </style>

</head>

<body>
    <div class="container">
        <div class="row">
            <?
            //choosing what to display
            //execute installation since they are posting data
            if ( $_POST  AND $is_compatible === TRUE)
            {
                //theres post, download latest version, unzip and rediret to install
                //download file
                file_put_contents('progress.json', '');
                $file_content = core::curl_get_contents($versions[$last_version]['download']);
                file_put_contents('oc.zip', $file_content);
                
                $zip = new ZipArchive;
                // open zip file, extract to dir
                if ($zip_open = $zip->open('oc.zip')) 
                {
                    if ( !($folder_update = $zip->getNameIndex(0)) )
                    {
                        hosting_view();
                        exit;
                    }

                    $zip->extractTo(DOCROOT);
                    $zip->close();  
                    
                    core::copy($folder_update, DOCROOT);
                    
                    // delete downloaded zip file
                    core::delete($folder_update);
                    @unlink('oc.zip');
                    @unlink($_SERVER['SCRIPT_FILENAME']);
                    
                    // redirect to install
                    header("Location: index.php");    
                }   
                else 
                    hosting_view();
            }
            //normally if compatible just display the form
            elseif ($is_compatible === TRUE)
            {?>
                <?if (!empty(install::$msg) OR !empty(install::$error_msg)) 
                        hosting_view();?>
            <?}
            //not compatible
            else
                hosting_view();
            ?>
            <div class="col-md-8 col-md-offset-2 animated fadeIn">
                <div class="row">
                    <div class="col-md-6">
                        <h2><a target="_blank" href="http://open-classifieds.com/"><img class="logo" src="http://open-classifieds.com/wp-content/uploads/2015/05/oc-logo-hd.png"></a></h2>
                        <p><strong><?=__("Welcome to the super easy and fast installation")?></strong></p>
                        <p>Open Classifieds is an open source powerful PHP classifieds script that can help you start a website and turn it into a fully customizable classifieds site within a few minutes.</p>
                        <div class="alert alert-services text-center">
                            <p><a target="_blank" href="http://open-classifieds.com/market/"><?=__("If you need any help please check our professional services")?></a></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="off-canvas animated">
                            <form method="post" action="">
                                <div class="panel-1">
                                    <h3><?=__("Software Requirements")?>  v.<?=$last_version;?></h3>
                                    <ul class="list-unstyled list-requirements">
                                        <?foreach (install::requirements() as $name => $values):
                                            $color = ($values['result'])?'success':'danger';?>
                                            <li data-color="<?=$color?>" data-result="<?=($values['result'])?"check":"times"?>">
                                                <span class="check">
                                                    <i class="fa fa-fw fa-spinner fa-pulse"></i>
                                                </span>
                                                <?=$name?>
                                            </li>
                                        <?endforeach?>
                                    </ul>
                                    <?if ($is_compatible === TRUE):?>
                                        <form method="post" action="">
                                            <p>
                                                <input type="hidden" name="dummy">
                                                <button class="btn btn-default btn-block submit" type="button">Download and Install</button>
                                            </p>
                                            <div class="progress hidden">
                                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <p>
                                                <small>
                                                    We will download Open Classifieds <?=$last_version?> and redirect you to the installation form.
                                                </small>
                                            </p>
                                        </form>
                                    <?endif?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row copyright">
                    <div class="col-md-6">
                        <p>Copyright <a target="_blank" href="http://open-classifieds.com/">Open Classifieds</a></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-right">&copy; 2009-<?=date('Y')?></p>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

    <script>
        $('#phpinfobutton').click(function(){
            if($('#phpinfo').hasClass('hidden'))
            {
                $(this).removeClass('label-info');
                $(this).addClass('label-warning');
                $('#phpinfo').removeClass('hidden');
            }
            else
            {
                $(this).removeClass('label-warning');
                $(this).addClass('label-info');
                $('#phpinfo').addClass('hidden');
            }
        });
        
        $('.list-requirements li').each(function(i){
            var l = $(this);
            var color = $(this).data('color');
            var result = $(this).data('result');
            setTimeout(function(){
                //l.show().addClass('animated fadeInRight');
                l.find('.check').html('<small><span class="animated bounceIn fa-stack text-' + color +'"><i class="fa fa-circle-o fa-stack-2x"></i><i class="fa fa-' + result + ' fa-stack-1x"></i></span></small>');
            }, (i+1) * 200);
        });
        
        $(".submit").click(function(event) {
            var btn = $(this)
            $(".progress").removeClass('hidden');
            btn.attr('disabled', 'disabled').addClass('hidden');
            setInterval(function(){ 
                $.get( 'progress.json', function(data) {
                    if (data.progress == 100)
                    {
                        $(".progress > div").width(data.progress + '%').html('Uncompressing...');
                        return;
                    }
                    
                    $(".progress > div").width(data.progress + '%').html(data.progress + '%');
                });
            }, 1500);
            $.ajax({
                url: $(location).attr('href'),
                type: 'post',
                data: $('form').serialize(),
                success: function() {
                    window.location.href = 'index.php';
                }
            });
        });

    </script>

</body>
</html>

<?
/**
 * displayed in case not compatible
 * @return [type] [description]
 */
function hosting_view()
{
    ?>
    <div class="col-md-8 col-md-offset-2">
        <div class="row alerts">
            <div class="col-md-12">
                <?if (!empty(install::$error_msg)):?>
                    <div class="alert alert-danger animated fadeInDown">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <?=install::$error_msg?>
                    </div>
                <?endif?>

                <?if(!empty(install::$msg)):?>
                    <?if(install::is_compatible()):?>
                        <div class="alert alert-info animated fadeInDown">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?=__("We have detected some incompatibilities, installation may not work as expected but you can try.")?>
                        </div>
                    <?endif?>
                    <?foreach(install::$msg as $msg):?>
                        <div class="alert alert-warning animated fadeInDown">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?=$msg?>
                        </div>
                    <?endforeach?>
                <?endif?>

                <div class="alert animated fadeInDown">
                    <p class="text-danger"><strong>Your hosting seems to be not compatible. Check your settings.</strong></p>
                    <p>We have partnership with hosting companies to assure compatibility. And we include:</p>
                    <br>
                    <ul>
                        <li>100% Compatible High Speed Hosting</li>
                        <li>1 Premium Theme, of your choice worth $185</li>
                        <li>Professional Installation and Support for 3 months</li>
                    </ul>
                    <br>
                    <p>
                        <a class="btn btn-default btn-large" href="http://open-classifieds.com/hosting/">
                            <i class=" icon-shopping-cart icon-white"></i> Get Hosting! Less than $4 Month
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?
}
?>
