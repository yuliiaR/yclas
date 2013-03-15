<? 
include 'functions.php';
include 'install.php';
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en>"> <!--<![endif]-->
<head>
	<meta charset="utf8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=(SAMBA)? 'Open Classifieds':''?> <?=_("Installation")?></title>
    <meta name="keywords" content="" >
    <meta name="description" content="" >
    <meta name="copyright" content="<?=(SAMBA)? 'Open Classifieds':''?> <?=VERSION?>" >
	<meta name="author" content="<?=(SAMBA)? 'Open Classifieds':''?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>    <![endif]-->
    
    <link type="text/css" href="" rel="stylesheet" media="screen" />	
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>	
	
	<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">

  </head>

  <body>
  
	<div class="navbar navbar-fixed-top navbar-inverse">
	<div class="navbar-inner">
	<div class="container"><a class="brand"><?=(SAMBA)? 'Open Classifieds':''?> <?=_("Installation")?></a>
	<div class="nav-collapse">

	<? if (SAMBA){?>
	<div class="btn-group pull-right">
		<a class="btn btn-primary" href="http://open-classifieds.com/download/">
			<i class="icon-shopping-cart icon-white"></i> We install it for you, Buy now!
		</a>
	</div>
	<? }?>

	</div>
	<!--/.nav-collapse --></div>
	</div>
	</div>    
	<div class="container">
		    <div class="row">
		    
			<div class="span3">
	<div class="well sidebar-nav">
	
		<ul class="nav nav-list">
			<li class="nav-header"><?=__("Requirements")?></li>
			<li class="divider"></li>
			
			<li class="nav-header"><?=__("Server software")?></li>

			<?foreach ($checks as $name => $values):
				if ($values['mandatory'] == TRUE AND $values['result'] == FALSE)
					$succeed = FALSE;

				if ($values['result'] == FALSE)
					$msg .= $values['message'].'<br>';
			?>

				<li><i class="icon-<?=($values['result'])?"ok":"remove"?>"></i> 
					<?=$name?>
				</li>
			<?endforeach?>

			<li class="divider"></li>
			<li><a href="install/phpinfo.php"><?=__("PHP Info")?></a></li>
			<li class="divider"></li>
			<? if (SAMBA){?>
			<li class="nav-header">Open Classifieds</li>
			<li><a href="http://open-classifieds.com/themes/">Themes</a></li>
			<li><a href="http://open-classifieds.com/download/">Support</a></li>
			<li><a href="http://j.mp/ocdonate" target="_blank">
					<img src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" alt="">
			</a></li>
			<li class="divider"></li>
			<? }?>
		</ul>
		<? if (SAMBA){?>
		<a href="https://twitter.com/openclassifieds"
				onclick="javascript:_gaq.push(['_trackEvent','outbound-widget','http://twitter.com']);"
				class="twitter-follow-button" data-show-count="false"
				data-size="large">Follow @openclassifieds</a><br />
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		<? }?>
		
	</div>
	<!--/.well -->
</div>
<!--/span-->	

<div class="span9">
<?if ($succeed){?>


<div class="page-header">
	<h1><?=__("Welcome to")?> Open Classifieds <?=__("installation")?></h1>
	<p>
		<?=__("Welcome to the super easy and fast installation")?>. 
		<?if (SAMBA):?>
			<a href="http://open-classifieds.com/download/" target="_blank">
			<?=__("If you need any help please check our professional services")?></a>.
		<?endif?>
	</p>	
</div>

<?if ($msg){?>
	<div class="alert alert-warning"><?=$msg?></div>
<?hostingAd();}?>

<form method="post" action="" class="well" >
<fieldset>

<div class="control-group">
	<label class="control-label"><?=__("Site Language")?></label>
	<div class="controls">
       <select name="LANGUAGE">

		<option value="en_EN">en_EN</option>
		    <?
		    $languages = scandir("languages");
		    foreach ($languages as $lang) {
			    
			    if( strpos($lang,'.')==false && $lang!='.' && $lang!='..' ){
				    if ($lang==$locale_language)  $sel= "selected=selected";
				    else $sel = "";
				    echo "<option $sel value=\"$lang\">$lang</option>";
			    }
		    }
		    ?>
		</select>
	</div>
</div>



<h2><?=__('Database Configuration')?></h2>

<div class="control-group">
	<label class="control-label"><?=__("Host name")?>:</label>
	<div class="controls">
	<input  type="text" name="DB_HOST" value="<?=cP('DB_HOST','localhost')?>" class="span6"  />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("User name")?>:</label>
	<div class="controls">
	<input  type="text" name="DB_USER"  value="<?=cP('DB_USER','root')?>" class="span6"   />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Password")?>:</label>
	<div class="controls">
	<input type="password" name="DB_PASS" value="<?=cP('DB_PASS')?>" class="span6" />		
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Database name")?>:</label>
	<div class="controls">
	<input type="text" name="DB_NAME" value="<?=cP('DB_NAME','openclassifieds')?>"  class="span6"  />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Database charset")?>:</label>
	<div class="controls">
	<input type="text" name="DB_CHARSET" value="<?=cP('DB_CHARSET','utf8')?>"  class="span6"   />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Table prefix")?>:</label>
	<div class="controls">
	<input type="text" name="TABLE_PREFIX" value="<?=cP('TABLE_PREFIX','oc_')?>" class="text-medium" />
	<span class="help-block"><?=__("Allows multiple installations in one database if you give each one a unique prefix")?>. <?=__("Only numbers, letters, and underscores")?>.</span>
	</div>
</div>


<div class="control-group">
	<label class="checkbox"><input type="checkbox" name="SAMPLE_DB"  value="1" /><?=__("Sample data")?></label>
	<span class="help-block"><?=__("Creates few sample categories and posts")?></span>
</div>

<h2><?=__('Basic Configuration')?></h2>

<div class="control-group">
	<label class="control-label"><?=__("Site Name")?>:</label>
	<div class="controls">
	<input  type="text" name="SITE_NAME" placeholder="<?=__("Site Name")?>" value="<?=cP('SITE_NAME')?>" class="span6" />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Time Zone")?>:</label>
	<div class="controls">
	<?=get_select_timezones('TIMEZONE',cP('TIMEZONE',date_default_timezone_get()))?>
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Admin email")?>:</label>
	<div class="controls">
	<input type="text" name="ADMIN=__MAIL" value="<?=cP('ADMIN_EMAIL','your@email.com')?>"  class="span6" />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Admin Password")?>:</label>
	<div class="controls">
	<input type="password" name="ADMIN_PWD" value="<?=cP('ADMIN_PWD')?>" class="span6" />	
	</div>
</div>

<div class="control-group">
	<label class="checkbox">
		<input type="checkbox" name="OCAKU" value="1" checked="checked" />
		<?=__("Ocaku registration")?> <a target="_blank" href="http://ocacu.com/en/terms.html">
			<?=__('Terms')?></a>
	</label>
	<span class="help-block"><?=__("Allow site to be in Ocaku, classifieds community (recommended)")?></span>	
</div>

<?if (SAMBA):?>
	<div class="control-group">
		<label class="checkbox"><input checked="checked" type="checkbox" id="terms" name="terms" value="1" />  <?=__("I accept the license terms")?>. </label>
		<span class="help-block"><a href="http://www.gnu.org/licenses/gpl.txt" target="_blank">GPL v3</a>
		<?=__("Please read the following license agreement and accept the terms to continue")?>
		</span>
	</div>
<?else:?>
	<input type="hidden" id="terms" name="terms" value="1" />
<?endif?>

<input type="submit" name="action" id="submit" value="<?=__("Install")?>" class="btn btn-primary btn-large" />

</fieldset>
</form>

<?
}//if requirements succeed

else{?>

	<div class="alert alert-error"><?=$msg?></div>
	<?hostingAd()?>

	<?if (SAMBA):?>
	<div class="hero-unit">
		<h2>Upgrade now!</h2>
		<p>Just for $69.90, Installation, commercial license, premium support, 13 premium themes and much more.</br>
			<a class="btn btn-primary btn-large" href="http://open-classifieds.com/download/"><i class=" icon-shopping-cart icon-white"></i> Buy now!</a>
		</p>
	</div>
	<?endif?>

<?}?>

</div><!--/span--> 
</div><!--/row-->
<hr>

<footer>
<p>
&copy; 
<?if (SAMBA):?>
	<a href="http://open-classifieds.com" title="Open Source PHP Classifieds">Open Classifieds</a> 2009 - 
<?endif?>
<?=date('Y')?>
</p>
</footer>    

</div><!--/.fluid-container-->
	
	<!--[if lt IE 7 ]>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->
  </body>
</html>