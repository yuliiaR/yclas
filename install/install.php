<?php
/**
 * Helper include to actually install
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2011 Open Classifieds Team
 * @license    GPL v3
 */

defined('SYSPATH') or exit('Install must be loaded from within index.php!');

//there was a submit from index.php
if ($_POST AND $succeed)
{
	$install 	= TRUE;
	
///////////////////////////////////////////////////////
	//check DB connection
	$link = mysql_connect($_POST['DB_HOST'], $_POST['DB_USER'], $_POST['DB_PASS']);
	if (!$link) 
	{
		$error_msg.= __('Cannot connect to server').' '. $_POST['DB_HOST'].' '. mysql_error();
		$install = FALSE;
	}
	
	if ($link && $install) 
	{
        if ($_POST['DB_NAME'])
        {
            $dbcheck = mysql_select_db($_POST['DB_NAME']);
            if (!$dbcheck)
            {
            	 $error_msg.= __('Database name').': ' . mysql_error();
            	 $install = FALSE;
        	}
        }
        else 
        {
    		$error_msg.= '<p>'.__('No database name was given').'. '.__('Available databases').':</p>\n';
    		$db_list = @mysql_query('SHOW DATABASES');
    		$error_msg.= '<pre>\n';
			if (!$db_list) 
			{
				$error_msg.= __('Invalid query'). ':\n' . mysql_error();
			}
			else 
			{
				while ($row = mysql_fetch_assoc($db_list)) 
				{
					$error_msg.= $row['Database'] . '\n';
				}
			}

    		$error_msg.= '</pre>\n';
            $install 	= FALSE;
        }
	}

	//save DB config/database.php
	if ($install)
	{
		$search  = array('[DB_HOST]', '[DB_USER]','[DB_PASS]','[DB_NAME]','[TABLE_PREFIX]','[DB_CHARSET]');
		$replace = array($_POST['DB_HOST'], $_POST['DB_USER'], $_POST['DB_PASS'],$_POST['DB_NAME'],$_POST['TABLE_PREFIX'],$_POST['DB_CHARSET']);
		$install = replace_file(APPPATH.'config/database.php',$search,$replace);
		if (!$install)
			$error_msg = __('Problem saving '.APPPATH.'config/database.php');
	}

	//install DB
	if ($install)
	{
		$sql_install_file = 'install/install.sql.php';
		//selecting the db
	    mysql_select_db($_POST['DB_NAME']);
	    mysql_query('SET NAMES '.$_POST['DB_CHARSET']);
	    
	    $hash_key = generate_password();

	    $search   = array('[TABLE_PREFIX]','[DB_CHARSET]','[ADMIN_EMAIL]',
	    					'[ADMIN_PWD]','[TIMEZONE]','[LANGUAGE]',
	    					'[HASH_KEY]','[SITE_NAME]','[SITE_URL]');
		$replace  = array($_POST['TABLE_PREFIX'],$_POST['DB_CHARSET'],$_POST['ADMIN_EMAIL'],
							$_POST['ADMIN_PWD'],$_POST['TIMEZONE'],$_POST['LANGUAGE'],
							$hash_key,$_POST['SITE_NAME'],$_POST['SITE_URL']);

	    $install  = replace_file($sql_install_file,$search,$replace);

	    if ($install)
	    	include $sql_install_file; 
	    else
			$error_msg = __('Problem saving '.$sql_install_file);  

	}

///////////////////////////////////////////////////////
	//AUTH config
	if ($install)
	{
		$search  = array('[HASH_KEY]', '[COOKIE_SALT]','[QL_KEY]');
		$replace = array($hash_key,generate_password(),generate_password());
		$install = replace_file(APPPATH.'config/auth.php',$search,$replace);
		if (!$install)
			$error_msg = __('Problem saving '.APPPATH.'config/auth.php');
	}

///////////////////////////////////////////////////////
	//create robots.txt
	if ($install)
	{
		$search  = array('[SITE_URL]', '[SITE_FOLDER]');
		$replace = array($_POST['SITE_URL'], $suggest_folder);
		$install = replace_file(DOCROOT.'robots.txt',$search,$replace);
		if (!$install)
			$error_msg = __('Problem saving '.DOCROOT.'robots.txt');
	}


///////////////////////////////////////////////////////
	//create htaccess
	//replace at example.htaccess and then we rename it
	if ($install)
	{
		$search  = array('[SITE_FOLDER]');
		$replace = array($suggest_folder);
		if ($install = replace_file(DOCROOT.'example.htaccess',$search,$replace))
			$install = rename(DOCROOT.'example.htaccess', DOCROOT.'.htaccess');

		if (!$install)
			$error_msg = __('Problem saving '.DOCROOT.'.htaccess');
	}


///////////////////////////////////////////////////////
	//ocaku register
	if ($install)
	{
		if ($_POST['OCAKU'] == 1)
	    {	        	
	    	include DOCROOT.'install/ocaku.php';
	        //ocaku register new site!
	        $ocaku = new ocaku();
	        $ocaku->newSite(array(
	        					'siteName'=>$_POST['SITE_NAME'],
	        					'siteUrl' =>$_POST['SITE_URL'],
	        					'email'   =>$_POST['ADMIN_EMAIL'],
	        					'language'=>substr($_POST['LANGUAGE'],0,2)
	        ));
	    }	
	}

///////////////////////////////////////////////////////
	//all good!
	if ($install) 
		unlink(DOCROOT.'install/install.lock');//prevents from performing a new install
	//else @todo mysql rollback??
	
	
	
}