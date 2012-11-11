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
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <?=HTML::script('http://html5shim.googlecode.com/svn/trunk/html5.js')?>
    <![endif]-->
    
    <?=View::styles($styles)?>	
	<?=View::scripts($scripts)?>

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
    <div class="container">
	    <div class="row">
	    
		<?=View::factory('admin/sidebar')?>
			<div class="span9">
				<?=Breadcrumbs::render('admin/breadcrumbs')?>      
				<?=Alert::show()?>
				<?=$content?>
	    	</div><!--/span--> 
		</div><!--/row-->
		<?=$footer?>
    </div><!--/.fluid-container-->

	<?=View::scripts($scripts,'footer')?>	
	
	<!--[if lt IE 7 ]>
		<?=HTML::script('http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js')?>
		<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
	<![endif]-->

  </body>
</html>
<?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>