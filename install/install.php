<?php

//there was a submit from index.php
if ($_POST AND $succeed)
{
	$install 	= TRUE;
	$error_msg 	= '';
	
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
	}

	//install DB
	if ($install)
	{
		$sql_install_file = 'install/install.sql.php';
		//selecting the db
	    mysql_select_db($_POST['DB_NAME']);
	    mysql_query('SET NAMES '.$_POST['DB_CHARSET']);
	    
	    $search  = array('[TABLE_PREFIX]','[DB_CHARSET]');
		$replace = array($_POST['TABLE_PREFIX'],$_POST['DB_CHARSET']);
	    $install = replace_file($sql_install_file,$search,$replace);

	    if ($install)
	    	include $sql_install_file;   


	}

///////////////////////////////////////////////////////
	//AUTH config
	if ($install)
	{
		$search  = array('[HASH_KEY]', '[COOKIE_SALT]','[QL_KEY]');
		$replace = array(generate_password(),generate_password(),generate_password());
		$install = replace_file(APPPATH.'config/auth.php',$search,$replace);
	}

	die();



    
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
	        					'siteUrl'=>$_POST['SITE_URL'],
	        					'email'=>$_POST['NOTIFY_EMAIL'],
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
	
///////////////////////////////////////////////////////
	//all good!
	if ($install) unlink('install/install.lock');//prevents from performing a new install
	
	//not succesful installation, let them know what was wrong
	if (!$install && !empty($error_msg)) {
		echo '<div class="alert alert-error">'.$error_msg.'</div>';
	}
	else{
		//let them know installation suceed
		?>
		<div class="alert alert-success"><?=__('Congratulations');?></div>
		<div class="hero-unit">
			<h1><?=__('Installation done');?></h1>
			<p>
				<?=__('Please now erase the folder');?> <code>/install/</code><br>
			
				<a class="btn btn-success btn-large" href="<?=$_POST['SITE_URL'];?>"><?=__('Go to Your Website')?></a>
				
				<a class="btn btn-warning btn-large" href="<?=$_POST['SITE_URL'];?>/admin">Admin</a> 
				<span class="help-block">user: <?=$_POST['ADMIN']?> pass: <?=$_POST['ADMIN_PWD']?></span>
				<hr>
				<a class='btn btn-primary btn-large" href="http://j.mp/ocdonate"><?=__('Make a donation')?></a>
				<?=__('We really appreciate it')?>.
			</p>
		</div>
		<?
	}
	
}