<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * initiating the Widgets modules
 *
 */

/**
 * Widgets that exists in the default module, add here if you create new ones. 
 * This way we do not scan the folder for widgets.
 * @var array
 */
widgets::$default_widgets = array('widget_rss', 'widget_pages','widget_text');

/**
 * placeholders that exists in the default module
 * @var array
 */
widgets::$default_placeholders	 = array('sidebar', 'inactive');