<?php
/*
Generate configs for:
DB
CACHE
AUTH

Create the DB tables if they don't exists

Then fill DB configs with commons config we need to run the app.
Theme
Locales 
Timezones
Cookie salt
etc...

Create a "God" user with role 10.
 */

// Sanity check, install should only be checked from index.php
defined('SYSPATH') or exit('Install tests must be loaded from within index.php!');

//prevents from new install to be done
if(!file_exists('install/install.lock')) die('Installation seems to be done');


define('SAMBA',TRUE);
define('VERSION','2.0 Beta');

if (isset($_POST["LANGUAGE"])) $locale_language=$_POST["LANGUAGE"];
elseif (isset($_GET["LANGUAGE"])) $locale_language=$_GET["LANGUAGE"];
else  $locale_language='en_EN';
//i18n::load($locale_language,'messages','/languages/',CHARSET);



$succeed = TRUE; 
$msg 	 = '';

$checks = array(

				'robots.txt'=>array('message'	=> 'The <code>robots.txt</code> file is not writable.',
									'mandatory'	=> FALSE,
									'result'	=> is_writable('robots.txt')
									),
				'.htaccess' =>array('message'	=> 'The <code>.htaccess</code> file is not writable.',
									'mandatory'	=> TRUE,
									'result'	=> is_writable('.htaccess')
									),
				'sitemap' 	=>array('message'	=> 'The <code>sitemap.xml.gz</code> file is not writable.',
									'mandatory'	=> FALSE,
									'result'	=> is_writable('sitemap.xml.gz')
									),
				'images' 	=>array('message'	=> 'The <code>images/</code> directory is not writable.',
									'mandatory'	=> TRUE,
									'result'	=> is_writable('images')
									),
				'cache' 	=>array('message'	=> 'The <code>'.APPPATH.'cache/</code> directory is not writable.',
									'mandatory'	=> TRUE,
									'result'	=> (is_dir(APPPATH) AND is_dir(APPPATH.'cache') AND is_writable(APPPATH.'cache'))
									),
				'logs' 		=>array('message'	=> 'The <code>'.APPPATH.'logs/</code> directory is not writable.',
									'mandatory'	=> TRUE,
									'result'	=> (is_dir(APPPATH) AND is_dir(APPPATH.'logs') AND is_writable(APPPATH.'logs'))
									),
				'SYS'	 	=>array('message'	=> 'The configured <code>'.SYSPATH.'</code> directory does not exist or does not contain required files.',
									'mandatory'	=> TRUE,
									'result'	=> (is_dir(SYSPATH) AND is_file(SYSPATH.'classes/kohana'.EXT))
									),
				'APP'   	=>array('message'	=> 'The configured <code>'.APPPATH.'</code> directory does not exist or does not contain required files.',
									'mandatory'	=> TRUE,
									'result'	=> (is_dir(APPPATH) AND is_file(APPPATH.'bootstrap'.EXT))
									),
				'PHP' 		=>array('message'	=> 'PHP 5.2.4 or newer required, this version is '. PHP_VERSION,
									'mandatory'	=> TRUE,
									'result'	=> version_compare(PHP_VERSION, '5.2.4', '>=')
									),
				'PCRE UTF8'	=>array('message'	=> '<a href="http://php.net/pcre">PCRE</a> has not been compiled with UTF-8 support.',
									'mandatory'	=> TRUE,
									'result'	=> (bool) (@preg_match('/^.$/u', 'ñ'))
									),
				'PCRE Unicode'=>array('message'	=> '<a href="http://php.net/pcre">PCRE</a> has not been compiled with Unicode property support.',
									'mandatory'	=> TRUE,
									'result'	=> (bool) (@preg_match('/^\pL$/u', 'ñ'))
									),
				'SPL'		=>array('message'	=> 'PHP <a href="http://www.php.net/spl">SPL</a> is either not loaded or not compiled in.',
									'mandatory'	=> TRUE,
									'result'	=> (function_exists('spl_autoload_register'))
									),
				'Reflection'=>array('message'	=> 'PHP <a href="http://www.php.net/reflection">reflection</a> is either not loaded or not compiled in.',
									'mandatory'	=> TRUE,
									'result'	=> (class_exists('ReflectionClass'))
									),
				'Filters'	=>array('message'	=> 'The <a href="http://www.php.net/filter">filter</a> extension is either not loaded or not compiled in.',
									'mandatory'	=> TRUE,
									'result'	=> (function_exists('filter_list'))
									),
				'Iconv'		=>array('message'	=> 'The <a href="http://php.net/iconv">iconv</a> extension is not loaded.',
									'mandatory'	=> TRUE,
									'result'	=> (extension_loaded('iconv'))
									),
				'Mbstring'	=>array('message'	=> 'The <a href="http://php.net/mbstring">mbstring</a> extension is not loaded.',
									'mandatory'	=> TRUE,
									'result'	=> (extension_loaded('mbstring'))
									),
				'CType'		=>array('message'	=> 'The <a href="http://php.net/ctype">ctype</a> extension is not enabled.',
									'mandatory'	=> TRUE,
									'result'	=> (function_exists('ctype_digit'))
									),
				'URI'		=>array('message'	=> 'Neither <code>$_SERVER[\'REQUEST_URI\']</code>, <code>$_SERVER[\'PHP_SELF\']</code>, or <code>$_SERVER[\'PATH_INFO\']</code> is available.',
									'mandatory'	=> TRUE,
									'result'	=> (isset($_SERVER['REQUEST_URI']) OR isset($_SERVER['PHP_SELF']) OR isset($_SERVER['PATH_INFO']))
									),
				'cUrl'		=>array('message'	=> 'OC requires the <a href="http://php.net/curl">cURL</a> extension for the Request_Client_External class.',
									'mandatory'	=> TRUE,
									'result'	=> (extension_loaded('curl'))
									),
				'mcrypt'	=>array('message'	=> 'OC requires the <a href="http://php.net/mcrypt">mcrypt</a> for the Encrypt class.',
									'mandatory'	=> TRUE,
									'result'	=> (extension_loaded('mcrypt'))
									),
				'GD'		=>array('message'	=> 'OC requires the <a href="http://php.net/gd">GD</a> v2 for the Image class',
									'mandatory'	=> TRUE,
									'result'	=> (function_exists('gd_info'))
									),
				'MySQL'		=>array('message'	=> 'OC requires the <a href="http://php.net/mysql">MySQL</a> extension to support MySQL databases.',
									'mandatory'	=> TRUE,
									'result'	=> (function_exists('mysql_connect'))
									),
				'PDO'		=>array('message'	=> 'OC requires the <a href="http://php.net/pdo">PDO</a> to support additional databases.',
									'mandatory'	=> TRUE,
									'result'	=>  (class_exists('PDO'))
									),

				);