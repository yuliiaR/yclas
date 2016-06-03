<?php defined('SYSPATH') or die('No direct access allowed.');
/**
  * Theme Name: Basecamp free
  * Description: The Basecamp free theme offers basic views and options. For more options consider buying the Basecamp premium theme which offers extended customization.  
  * Tags: HTML5, Responsive, Mobile
  * Version: 2.7.0
  * Author: Thom <thom@open-classifieds.com>
  * License: Free
  * Parent Theme: default
  * Skins: default, mint, charcoal/gold, plumb
  */


/**
 * placeholders for this theme
 */
Widgets::$theme_placeholders	= array('footer', 'sidebar');

/**
 * custom options for the theme
 * @var array
 */
Theme::$options = Theme::get_options();

//we load earlier the theme since we need some info
Theme::load();

/**
 * styles and themes, loaded in this order
 */
Theme::$skin = Theme::get('theme');

/**
 * styles and themes, loaded in this order
 */

//local files

$theme_css =  array(      'css/bootstrap.min.css' => 'screen',
                          'css/font-awesome.min.css' => 'screen',
                          'css/bootstrap-image-gallery.min.css' => 'screen',
                          'css/blueimp-gallery.min.css' => 'screen', 
                          'css/datepicker.css' => 'screen',
                          'css/chosen.min.css' => 'screen',                        
                          'css/slider.css' => 'screen',
                          'css/styles.css?v='.Core::VERSION => 'screen',
                  );

if ($body_font = explode('|', Theme::get('body_font'))[0])
  $theme_css = $theme_css + [$body_font => 'screen'];

if (Theme::$skin!='default')
      $theme_css = array_merge($theme_css, array('css/color-'.Theme::$skin.'.css' => 'screen'));

Theme::$styles = $theme_css;

Theme::$scripts['footer'] = array(  'js/jquery-1.10.2.js',
                                    'js/bootstrap.min.js',
                                    'js/chosen.jquery.min.js',
                                    Route::url('jslocalization', array('controller'=>'jslocalization', 'action'=>'chosen')),
                                    'js/jquery.validate.min.js',
                                    Route::url('jslocalization', array('controller'=>'jslocalization', 'action'=>'validate')),
                                    'js/blueimp-gallery.min.js',
                                    'js/bootstrap-image-gallery.min.js',
                                    'js/bootstrap-datepicker.js',
                                    'js/holder.min.js',
                                    'js/bootstrap-slider.js',
                                    'js/favico-0.3.8.min.js',
                                    'js/default.init.js?v='.Core::VERSION,
                                    'js/theme.init.js?v='.Core::VERSION,
                            );


/**
* load assets for yclas top bar
*/
if (method_exists('Core','yclas_url') AND Model_Domain::current()->id_domain != '1' AND Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN)
{
    Theme::$styles['css/yclas-topbar.css'] = 'screen';
    Theme::$scripts['footer'][] = (Core::is_HTTPS() ? 'https://' : 'http://').Model_Domain::get_sub_domain().'/panel/site/bar/'.Model_Domain::current()->id_domain;
    Theme::$scripts['footer'][] = 'js/yclas-topbar.js';
}

/**
 * custom error alerts
 */
Form::$errors_tpl 	= '<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a>
			       		<h4 class="alert-heading">%s</h4>
			        	<ul>%s</ul></div>';

Form::$error_tpl 	= '<div class="alert "><a class="close" data-dismiss="alert">×</a>%s</div>';


Alert::$tpl 	= 	'<div class="alert alert-%s">
					<a class="close" data-dismiss="alert" href="#">×</a>
					<h4 class="alert-heading">%s</h4>%s
					</div>';