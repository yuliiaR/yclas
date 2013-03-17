<?php
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

/*

 @todo newsletter register, adserum register
///////////////////////////////////////////////////////
	//ocaku register
	if ($install)
	{
		if ($_POST['OCAKU'] == 1)
	    {	        	    
	        //ocaku register new site!
	        $ocaku=new ocaku();
	        $data=array(
	        					'siteName'=>$_POST['SITE_NAME'],
	        					'siteUrl' =>$_POST['SITE_URL'],
	        					'email'   =>$_POST['ADMIN_EMAIL'],
	        					'language'=>substr($_POST['LANGUAGE'],0,2)
	        );
	        $apiKey=$ocaku->newSite($data);
	        unset($ocaku);
	        //end ocaku register
	    }	
	    else $apiKey='';
	}

///////////////////////////////////////////////////////
	//create robots.txt
	if ($install)
	{
		if (!regenerateRobots($_POST['SITE_URL']))
		{
			$error_msg = __('Cannot write to the configuration file').' /robots.txt';
			$install=FALSE;
		}
	}
	
///////////////////////////////////////////////////////
	//create htaccess
	if ($install)
	{		
		if (!regenerateHtaccess($_POST['SITE_URL']))
		{
			$error_msg = __('Cannot write to the configuration file').'/.htaccess';
			$install=FALSE;
		}
	}
	*/

///////////////////////////////////////////////////////
	//all good!
	if ($install) unlink('install/install.lock');//prevents from performing a new install
	
	
	
}