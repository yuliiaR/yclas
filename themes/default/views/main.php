<?php defined('SYSPATH') or die('No direct script access.');?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?=substr(core::config('i18n.locale'),0,2)?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?=substr(core::config('i18n.locale'),0,2)?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?=substr(core::config('i18n.locale'),0,2)?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=substr(core::config('i18n.locale'),0,2)?>"> <!--<![endif]-->
<head>
	<meta charset="<?=Kohana::$charset?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$title?></title>
    <meta name="keywords" content="<?=$meta_keywords?>" >
    <meta name="description" content="<?=$meta_description?>" >
    <meta name="copyright" content="<?=$meta_copywrite?>" >
	<meta name="author" content="open-classifieds.com">
	<meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="alternate" type="application/atom+xml" title="RSS <?=Core::config('general.site_name')?>" href="<?=Route::url('rss')?>" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
    <!--[if lt IE 9]>
      <?=HTML::script('http://html5shim.googlecode.com/svn/trunk/html5.js')?>
    <![endif]-->
    
    <?=View::styles($styles)?>	
	<?=View::scripts($scripts)?>

    <link rel="shortcut icon" href="<?=View::public_path('img/favicon.ico')?>">

  </head>

  <body>
  
	<?=$header?>
    <div class="container">
        <div class="row">
            
            <div class="span9">
                <?=Alert::show()?>
                <?=$content?>
            </div><!--/span-->
            <div class="span3 pull-right"> 
            <?=View::factory('sidebar')?>
            </div>
        </div><!--/row-->
        <?=$footer?>
    </div><!--/.fluid-container-->


	<?=View::scripts($scripts,'footer')?>
	
    <?if ( core::config('general.analytics')!='' AND Kohana::$environment === Kohana::PRODUCTION ): ?>
    <script>
		var _gaq=[['_setAccount','<?=Core::config('general.analytics')?>'],['_trackPageview']]; 
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
    <?endif?>
	
	<!--[if lt IE 7 ]>
		<?=HTML::script('http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js')?>
		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->
  <?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>
  </body>
</html>