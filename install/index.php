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

// Sanity check, install should only be checked from index.php
defined('SYSPATH') or exit('Install must be loaded from within index.php!');

//were the install files are located
define('INSTALLROOT', DOCROOT.'install/');

//prevents from new install to be done
if(!file_exists(INSTALLROOT.'install.lock')) 
    die('Installation seems to be done, please remove /install/ folder');

//error_reporting(E_ALL);

include 'class.install.php';

//start the install setup
install::initialize();
$is_compatible = install::is_compatible();

//choosing what to display
//execute installation since they are posting data
if ( ($_POST OR isset($_GET['SITE_NAME'])) AND $is_compatible === TRUE)
    $view = (install::execute()===TRUE)?'success':'form';
//normally if its compaitble just display the form
elseif ($is_compatible === TRUE)
    $view = 'form';
//not compatible
else
    $view = 'hosting';
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
    <meta name="copyright" content="Open Classifieds <?=install::version?>" >
    <meta name="author" content="Open Classifieds">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="shortcut icon" href="http://open-classifieds.com/wp-content/uploads/2012/04/favicon1.ico" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>    <![endif]-->
       
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
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chosen/1.1.0/chosen.min.css">

</head>

<body>

    <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">
            <div class="container">
                <a class="navbar-brand" href="http://open-classifieds.com"><img src="http://open-classifieds.com/wp-content/uploads/2012/04/OC_noTagline_286x52.png" alt="Open Classifieds <?=__("Installation")?>"></a>
                <div class="nav-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#home" data-toggle="tab">Install</a></li>
                        <li><a href="#requirements" data-toggle="tab">Requirements</a></li>
                        <li><a href="#phpinfo" data-toggle="tab">phpinfo()</a></li>
                        <li><a href="http://open-classifieds.com/support/" target="_blank">Support</a></li>
                        <li><a href="#about" data-toggle="tab">About</a></li>
                    </ul>

                    <div class="btn-group pull-right">
                        <a class="btn btn-primary we-install" href="http://open-classifieds.com/market/">
                            <i class="glyphicon-shopping-cart glyphicon"></i> <?=__("We install it for you, Buy now!")?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>    

    <div class="container">

        <div class="tab-content">
            <div class="tab-pane fade in active" id="home">
                <?install::view($view)?>
            </div>
            <div class="tab-pane fade" id="requirements">
                <?install::view('requirements')?>
            </div>
            <div class="tab-pane fade" id="phpinfo">
                <?=str_replace('<table', '<table class="table table-striped table-bordered"', install::phpinfo())?>
            </div>
            <div class="tab-pane fade" id="about">
                <?install::view('about')?>
            </div>
        </div>
           
        <hr>

        <footer>
            <p>
            &copy;  <a href="http://open-classifieds.com" title="Open Source PHP Classifieds">Open Classifieds</a> 2009 - <?=date('Y')?>
            </p>
        </footer>    

    </div><!--/.fluid-container-->
    
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/jquery.bootstrapvalidation/1.3.7/jqBootstrapValidation.min.js"></script>
    <script src="//cdn.jsdelivr.net/chosen/1.1.0/chosen.jquery.min.js"></script>

    <script>
        $(function () { 
            $("select").chosen();
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } 
        );
    </script>

</body>
</html>