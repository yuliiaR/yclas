<?php defined('SYSPATH') or die('No direct script access.');?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="<?=Kohana::$charset?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$title?></title>
    <meta name="keywords" content="<?=$meta_keywords?>" >
    <meta name="description" content="<?=$meta_description?>" >
    <meta name="copyright" content="<?=$meta_copywrite?>" >
	<meta name="author" content="Open Classifieds">
	<meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="canonical" href="http://@todo add current uri" />
	<link rel="alternate" type="application/atom+xml" title="RSS" href="http://@todo" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <?=HTML::script('http://html5shim.googlecode.com/svn/trunk/html5.js')?>
    <![endif]-->
    
    <?=View::styles($styles)?>	
	  <?=View::scripts($scripts)?>

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?=View::public_path('img/favicon.ico')?>">
    <link rel="apple-touch-icon" href="<?=View::public_path('img/bootstrap-apple-57x57.png')?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=View::public_path('img/bootstrap-apple-72x72.png')?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=View::public_path('img/bootstrap-apple-114x114.png')?>">

    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>

  </head>

  <body>
  
	<?=$header?>
    <div class="container-fluid">
    <?=Alert::show()?>
		<?=$content?>
	    <?=$footer?>
    </div><!--/.fluid-container-->

	<?=View::scripts($scripts,'footer')?>
	
  <script>
    $(function (){
        //Theme.init ();
        $("select").chosen();
    });//@todo somewhere else
  </script> 
	
  <script>
		var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']]; // @todo Change UA-XXXXX-X to be your site's ID
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
	
	<!--[if lt IE 7 ]>
		<?=HTML::script('http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js')?>
		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->
  <?php if (Kohana::$environment === Kohana::DEVELOPMENT) ProfilerToolbar::render(true); ?>
  </body>
</html>

