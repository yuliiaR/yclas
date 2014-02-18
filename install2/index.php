<? 
/**
 * HTML template for the install
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */
include 'class.install.php';
include 'bootstrap.php';
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en>"> <!--<![endif]-->
<head>
    <meta charset="utf8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?=install::name?> <?=__("Installation")?></title>
    <meta name="keywords" content="" >
    <meta name="description" content="" >
    <meta name="copyright" content="<?=install::name?> <?=install::version?>" >
    <meta name="author" content="<?=install::name?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="shortcut icon" href="<?=install::favicon?>" />


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
      .we-install{margin-top: 8px;}
      #myTab{margin-bottom: 20px; margin-top: 20px;}
    </style>
        
    <link href="//netdna.bootstrapcdn.com/bootswatch/3.1.0/flatly/bootstrap.min.css" rel="stylesheet">

  </head>

  <body>

    <div class="navbar navbar-fixed-top navbar-inverse">
    <div class="navbar-inner">
    <div class="container"><a class="navbar-brand"><?=install::name?> <?=__("Installation")?></a>
    <div class="nav-collapse">

    <div class="btn-group pull-right">
        <a class="btn btn-primary we-install" href="http://open-classifieds.com/market/">
            <i class="glyphicon-shopping-cart glyphicon"></i> <?=__("We install it for you, Buy now!")?>
        </a>
    </div>

    </div>
    <!--/.nav-collapse --></div>
    </div>
    </div>    

    <div class="container">
        
        <ul class="nav nav-tabs">
            <li class="active"><a href="#home" data-toggle="tab">Install</a></li>
            <li><a href="#requirements" data-toggle="tab">Requirements</a></li>
            <li><a href="http://open-classifieds.com/support/" target="_blank">Support</a></li>
            <li><a href="#about" data-toggle="tab">About</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade in active" id="home">
                <?install::view($view)?>
            </div>
            <div class="tab-pane fade" id="requirements">
                <?install::view('requirements')?>
            </div>
            <div class="tab-pane fade" id="about">
                <?install::view('about')?>
            </div>
        </div>

           
        <hr>

        <footer>
            <p>
            &copy;  <a href="http://open-classifieds.com" title="Open Source PHP Classifieds"><?=install::name?></a> 2009 - <?=date('Y')?>
            </p>
        </footer>    

    </div><!--/.fluid-container-->
    
    <script type="text/javascript" src="themes/default/js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="themes/default/js/bootstrap.min.js"></script>

  </body>
</html>