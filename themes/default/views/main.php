<?php defined('SYSPATH') or die('No direct script access.');?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=i18n::html_lang()?>"> <!--<![endif]-->
<head>
	<meta charset="<?=Kohana::$charset?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$title?></title>
    <meta name="keywords" content="<?=$meta_keywords?>" >
    <meta name="description" content="<?=HTML::chars($meta_description)?>" >
    <meta name="copyright" content="<?=HTML::chars($meta_copyright)?>" >
	<meta name="author" content="open-classifieds.com">

    <?if (Controller::$image!==NULL):?>
    <meta property="og:image"   content="<?=core::config('general.base_url').Controller::$image?>"/>
    <?endif?>
    <meta property="og:title"   content="<?=HTML::chars($title)?>"/>
    <meta property="og:description"   content="<?=HTML::chars($meta_description)?>"/>
    <meta property="og:url"     content="<?=URL::current()?>"/>
    <meta property="og:site_name" content="<?=HTML::chars(core::config('general.site_name'))?>"/>
    
    <?if (core::config('general.disallowbots')=='1'):?>
        <meta name="robots" content="noindex,nofollow,noodp,noydir" />
        <meta name="googlebot" content="noindex,noarchive,nofollow,noodp" />
        <meta name="slurp" content="noindex,nofollow,noodp" />
        <meta name="bingbot" content="noindex,nofollow,noodp,noydir" />
        <meta name="msnbot" content="noindex,nofollow,noodp,noydir" />
    <?endif?>

    <?if (core::config('general.blog')==1):?>
    <link rel="alternate" type="application/atom+xml" title="RSS Blog <?=HTML::chars(Core::config('general.site_name'))?>" href="<?=Route::url('rss-blog')?>" />
    <?endif?>
    <?if (core::config('general.forums')==1):?>
    <link rel="alternate" type="application/atom+xml" title="RSS Forum <?=HTML::chars(Core::config('general.site_name'))?>" href="<?=Route::url('rss-forum')?>" />
      <?if (Model_Forum::current()->loaded()):?>
      <link rel="alternate" type="application/atom+xml" title="RSS Forum <?=HTML::chars(Core::config('general.site_name'))?> - <?=Model_Forum::current()->name?>" href="<?=Route::url('rss-forum', array('forum'=>Model_Forum::current()->seoname))?>" />
      <?endif?>
    <?endif?>
    <link rel="alternate" type="application/atom+xml" title="RSS <?=HTML::chars(Core::config('general.site_name'))?>" href="<?=Route::url('rss')?>" />


    <?if (Model_Category::current()->loaded() AND Model_Location::current()->loaded()):?>
    <link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Category::current()->name)?> - <?=Model_Location::current()->name?>"  href="<?=Route::url('rss',array('category'=>Model_Category::current()->seoname,'location'=>Model_Location::current()->seoname))?>" />
    <?elseif (Model_Location::current()->loaded()):?>
    <link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Location::current()->name)?>"  href="<?=Route::url('rss',array('category'=>URL::title(__('all')),'location'=>Model_Location::current()->seoname))?>" />
    <?elseif (Model_Category::current()->loaded()):?>
    <link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Category::current()->name)?>"  href="<?=Route::url('rss',array('category'=>Model_Category::current()->seoname))?>" />
    <?endif?>     
        
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
    <!--[if lt IE 9]>
      <?=HTML::script('http://html5shim.googlecode.com/svn/trunk/html5.js')?>
    <![endif]-->
    
    <?=Theme::styles($styles)?>	
	<?=Theme::scripts($scripts)?>
    <?if ( Kohana::$environment === Kohana::PRODUCTION AND core::config('general.analytics')!=='' ): ?>
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '<?=Core::config('general.analytics')?>']);
      _gaq.push(['_setDomainName', '<?=$_SERVER['SERVER_NAME']?>']);
      _gaq.push(['_setAllowLinker', true]);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script> 
    <?endif?>
    <link rel="shortcut icon" href="<?=core::config('general.base_url').'images/favicon.ico'?>">

    
  </head>

  <body data-spy="scroll" data-target=".subnav" data-offset="50">

    <?if(!isset($_COOKIE['accept_terms']) AND core::config('general.alert_terms') != ''):?>
        <?=View::factory('alert_terms')?>
    <?endif?>

	<?=$header?>
    <div class="container bs-docs-container">
    <div class="alert alert-warning off-line" style="display:none;"><strong><?=__('Warning')?>!</strong> <?=__('We detected you are currently off-line, please connect to gain full experience.')?></div>
        <div class="row">
     
            <div class="col-xs-9">
                <?=Breadcrumbs::render('breadcrumbs')?>
                <?=Alert::show()?>
                <?=$content?>
            </div><!--/span-->
            <?= FORM::open(Route::url('search'), array('class'=>'col-xs-3', 'method'=>'GET', 'action'=>''))?>
                <div class="form-group">
                    <input type="text" name="search" class="search-query form-control" placeholder="<?=__('Search')?>">
                </div>  
            <?= FORM::close()?>
            <?=View::fragment('sidebar_front','sidebar')?>
        </div><!--/row-->
        <?=$footer?>
    </div><!--/.fluid-container-->
  
  <?=Theme::scripts($scripts,'footer')?>
	
		

  <?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>
  </body>
</html>
