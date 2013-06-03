<? 
/**
 * HTML for the install
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
error_reporting(0);
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

	<title>Open Classifieds <?=__("Installation")?></title>
    <meta name="keywords" content="" >
    <meta name="description" content="" >
    <meta name="copyright" content="Open Classifieds <?=VERSION?>" >
	<meta name="author" content="Open Classifieds">
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<link rel="shortcut icon" href="http://open-classifieds.com/wp-content/uploads/2012/04/favicon1.ico" />


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
    	
	<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">

  </head>

  <body>

    <!--phpinfo Modal -->
    <div id="phpinfoModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-body">
        <?
        //getting the php info clean!
        ob_start();                                                                                                        
        phpinfo();                                                                                                     
        $phpinfo = ob_get_contents();                                                                                         
        ob_end_clean();  
        //strip the body html                                                                                                  
        $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
        //adding our class
        echo str_replace('<table', '<table class="table table-striped  table-bordered"', $phpinfo);
        ?>
      </div>
    </div>
    <!--END phpinfo Modal -->


	<div class="navbar navbar-fixed-top navbar-inverse">
	<div class="navbar-inner">
	<div class="container"><a class="brand">Open Classifieds <?=__("Installation")?></a>
	<div class="nav-collapse">

	<div class="btn-group pull-right">
		<a class="btn btn-primary" href="http://open-classifieds.com/download/">
			<i class="icon-shopping-cart icon-white"></i> We install it for you, Buy now!
		</a>
	</div>

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
            			<li><a href="#phpinfoModal" role="button" data-toggle="modal">PHP Info</a></li>
            			<li class="divider"></li>
            			
            			<li class="nav-header">Open Classifieds</li>
            			<li><a href="http://open-classifieds.com/themes/">Themes</a></li>
            			<li><a href="http://open-classifieds.com/">Support & More</a></li>
            			<li><a href="http://j.mp/thanksdonate" target="_blank">
            					<img src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" alt="">
            			</a></li>
            			<li class="divider"></li>
            			
            		</ul>
            		
            		<a href="https://twitter.com/openclassifieds"
            				onclick="javascript:_gaq.push(['_trackEvent','outbound-widget','http://twitter.com']);"
            				class="twitter-follow-button" data-show-count="false"
            				data-size="large">Follow @openclassifieds</a><br />
            			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            		
            		
            	</div>
            	<!--/.well -->
            </div>
            <!--/span-->	

<div class="span9">
<?if ($_POST && $succeed):?>

	<?if (!$install && !empty($error_msg)):?>
		 <div class="alert alert-error"><?=$error_msg?></div>
		<?hostingAd()?>
	<?elseif($install==TRUE):?>
		<div class="alert alert-success"><?=__('Congratulations');?></div>
		<div class="hero-unit">
			<h1><?=__('Installation done');?></h1>
			<p>
				<?=__('Please now erase the folder');?> <code>/install/</code><br>
			
				<a class="btn btn-success btn-large" href="<?=$_POST['SITE_URL']?>"><?=__('Go to Your Website')?></a>
				
				<a class="btn btn-warning btn-large" href="<?=$_POST['SITE_URL']?>oc-panel/home/">Admin</a> 
				<?if($_POST['ADMIN_EMAIL'])?><span class="help-block">user: <?=$_POST['ADMIN_EMAIL']?> pass: <?=$_POST['ADMIN_PWD']?></span>
				<hr>
				<a class="btn btn-primary btn-large" href="http://j.mp/thanksdonate"><?=__('Make a donation')?></a>
				<?=__('We really appreciate it')?>.
			</p>
		</div>
	<?endif?>

<?elseif ($succeed):?>

<div class="page-header">
	<h1><?=__("Welcome to")?> Open Classifieds <?=__("installation")?></h1>
	<p>
		<?=__("Welcome to the super easy and fast installation")?>. 
			<a href="http://open-classifieds.com/download/" target="_blank">
			<?=__("If you need any help please check our professional services")?></a>.
	</p>	
</div>

<?if ($msg){?>
	<div class="alert alert-warning"><?=$msg?></div>
<?hostingAd();}?>

<form method="post" action="" class="well" >
<fieldset>

<h2><?=__('Site Configuration')?></h2>

<div class="control-group">
	<label class="control-label"><?=__("Site Language")?></label>
	<div class="controls">
       <select name="LANGUAGE" onchange="window.location.href='?LANGUAGE='+this.options[this.selectedIndex].value">
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

<div class="control-group">
	<label class="control-label"><?=__("Site URL");?>:</label>
	<div class="controls">
    <input  type="text" size="75" name="SITE_URL" value="<?=cP('SITE_NAME',$suggest_url)?>"  class="span6" />
	</div>
</div>

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


<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a href="#install" data-toggle="tab"><?=__('New Install')?></a></li>
  <li><a href="#upgrade" data-toggle="tab"><?=__('Upgrade System')?></a></li>
</ul>
 
<div class="tab-content">

    <div class="tab-pane active" id="install">
        <div class="control-group">
            <label class="control-label"><?=__("Administrator email")?>:</label>
            <div class="controls">
                <input type="text" name="ADMIN_EMAIL" value="<?=cP('ADMIN_EMAIL')?>" placeholder="your@email.com" class="span6" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><?=__("Admin Password")?>:</label>
            <div class="controls">
                <input type="text" name="ADMIN_PWD" value="<?=cP('ADMIN_PWD')?>" class="span6" />   
            </div>
        </div>

        <div class="control-group">
            <label class="checkbox"><input type="checkbox" name="SAMPLE_DB" checked /><?=__("Sample data")?></label>
            <span class="help-block"><?=__("Creates few sample categories to start with")?></span>
        </div>
        
    </div>

    <div class="tab-pane" id="upgrade">
        <div class="control-group">
            <label class="control-label"><?=__("Hash Key")?>:</label>
            <div class="controls">
                <input type="text" name="HASH_KEY" value="<?=cP('HASH_KEY')?>" class="span6" />   
                <span class="help-block"><?=__('You need the Hash Key to re-install. You can find this value if you lost it at')?> <code>/oc/config/auth.php</code></span>
            </div>
        </div>
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
	<input type="text" name="DB_PASS" value="<?=cP('DB_PASS')?>" class="span6" />		
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
	<input type="text" name="TABLE_PREFIX" value="<?=cP('TABLE_PREFIX','oc2_')?>" class="text-medium" />
	<span class="help-block"><?=__("Allows multiple installations in one database if you give each one a unique prefix")?>. <?=__("Only numbers, letters, and underscores")?>.</span>
	</div>
</div>


<div class="form-actions">

	<input type="submit" name="action" id="submit" value="<?=__("Install")?>" class="btn btn-primary btn-large" />
    <hr>
    <div class="control-group">
        <label class="checkbox">
            <input type="checkbox" name="OCAKU" checked />
            <?=__("Ocacu classifieds community registration")?> <a target="_blank" href="http://ocacu.com/en/terms.html">
                <?=__('Terms')?></a>
        </label>
    </div>
</div>

</fieldset>
</form>

<?else:?>

	<div class="alert alert-error"><?=$msg?></div>
	<?hostingAd()?>

<?endif?>

</div><!--/span--> 
</div><!--/row-->
<hr>

<footer>
<p>
&copy; 	<a href="http://open-classifieds.com" title="Open Source PHP Classifieds">Open Classifieds</a> 2009 - <?=date('Y')?>
</p>
</footer>    

</div><!--/.fluid-container-->
	
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    $(function  () {
        $('.modal').css({
          'width': function () { 
            return ($(document).width() * .7) + 'px';  
          },
          'margin-left': function () { 
            return -($(this).width() / 2); 
          },
          //'max-height': '800px';
        });
    })
    </script>
	<!--[if lt IE 7 ]>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->
  </body>
</html>