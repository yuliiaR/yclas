<meta charset="<?=Kohana::$charset?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title><?=$title?></title>
<meta name="keywords" content="<?=$meta_keywords?>" >
<meta name="description" content="<?=HTML::chars($meta_description)?>" >
<meta name="copyright" content="<?=HTML::chars($meta_copyright)?>" >
<?if (Theme::get('premium')!=1):?>
<meta name="author" content="open-classifieds.com">
<?endif?>

<?if (Controller::$image!==NULL):?>
<meta property="og:image"   content="<?=core::config('general.base_url').Controller::$image?>"/>
<?elseif(Theme::get('logo_url')!=NULL):?>
<meta property="og:image"   content="<?=Theme::get('logo_url')?>"/>
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

<link rel="shortcut icon" href="<?=(Theme::get('favicon_url')!='') ? Theme::get('favicon_url') : core::config('general.base_url').'images/favicon.ico'?>">