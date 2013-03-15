<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en>"> <!--<![endif]-->
<head>
	<meta charset="utf8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=(SAMBA)? 'Open Classifieds':'';?> <?=_("Installation");?></title>
    <meta name="keywords" content="" >
    <meta name="description" content="" >
    <meta name="copyright" content="<?=(SAMBA)? 'Open Classifieds':'';?> <?=VERSION;?>" >
	<meta name="author" content="<?=(SAMBA)? 'Open Classifieds':'';?>">
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
<div class="container"><a class="brand"><?=(SAMBA)? 'Open Classifieds':'';?> <?=_("Installation");?></a>
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
</div>    <div class="container">
	    <div class="row">
	    
		<div class="span3">
	<div class="well sidebar-nav">
	
		<ul class="nav nav-list">
			<li class="nav-header"><?=__("Requirements");?></li>
			<li class="divider"></li>
			
			<li class="nav-header"><?=__("Server software");?></li>

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
			<li><a href="install/phpinfo.php"><?=__("PHP Info");?></a></li>
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
