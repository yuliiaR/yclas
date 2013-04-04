<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * RSS widget reader
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */


class Widget_RSS extends Widget
{

	//maybe instead of controller, set info?
	public function __construct()
	{	

		$this->title = 'RSS reader';
		$this->description = 'Reads the URL';

		$this->fields = array(	'rss_limit' => array( 	'type'		=> 'numeric',
														'display'	=> 'select',
														'label'		=> __('Number items to display'),
														'options'   => range(1,100), 
														'default'	=> 5,
														'required'	=> TRUE),

								'rss_expire' => array( 	'type'		=> 'numeric',
														'display'	=> 'text',
														'label'		=> __('How often we refresh the RSS, in seconds'),
														'default'	=> 86400,
														'required'	=> TRUE),

						 		'rss_url'  => array(	'type'		=> 'uri',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('RSS url address'),
						 		  						'default'   => 'http://feeds.feedburner.com/OpenClassifieds',
														'required'	=> TRUE),

						 		'rss_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('RSS title displayed'),
						 		  						'default'   => 'Open Classifieds',
														'required'	=> TRUE),
						 		);
	}

	
	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		//try to get the RSS from the cache
		$rss = Kohana::cache($this->rss_url,NULL,$this->rss_expire);

		//not cached :(
		if ($rss === NULL)
		{
			$rss = Feed::parse($this->rss_url,$this->rss_limit);
			Kohana::cache($this->rss_url,$rss,$this->rss_expire);
		}

		$this->rss_items = $rss;
	}


}