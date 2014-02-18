<?
/**
 * Helper install boot file used as "router"
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

// Sanity check, install should only be checked from index.php
defined('SYSPATH') or exit('Install must be loaded from within index.php!');

//prevents from new install to be done
if(!file_exists(DOCROOT.'install2/install.lock')) 
    die('Installation seems to be done, please remove /install/ folder');

 //nothing to report we will display if there's anything wrong? @todo to check
        //error_reporting(0);


//bool to see if the isntallation was good
            $install = FALSE;
        //installation error messages here
            $error_msg  = '';

        //requirements checks correct?
            $succeed = TRUE; 
        //message to explain what was not correct
            $msg     = '';


install::initialize();

//choosing what to display
if ($_POST && $succeed)
    install::install();
    $view = 'success';
elseif ($succeed)
    $view = 'form';
else
    $view = 'hosting';