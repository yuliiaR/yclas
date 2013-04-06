<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Abstract class Widget to use in all the other widgets
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 *
 * widget storage:
 * group: placeholder
 * name: placeholder name (unique)
 * value: json array in unique name order array('rss_09w0w309','text_ierijereijr')
 *
 * widget single storage
 * group: widget
 * name: rss_39394349349
 * value: json array: type of class, created, data('field_name'=>'value','rss_url'=>'http://rss.com')
 * 
 */

abstract class Widget{

	/**
	 * array fields the widget have, defined in the construct
	 * ex:
	 * array(	  'rss_items' => array( 'type'		=> 'numeric',
	 *													'display'	=> 'select',
	 *													'label'		=> __('# items to display'),
	 *													'options'   => range(1,10), 
	 *													'required'	=> TRUE),);
	 * @var array
	 */
	public $fields = array();


	/**
	 * data stored for each field
	 * @var array
	 */
	protected $data = array();
	

	/**
	 * limit placeholders for this widget 
	 * (leave empty array for NO restrictions )
	 * 
	 * @var array
	 */
	public $banned_placeholder = array();


	/**
	 * @var  widget title
	 */
	public $title = 'Widget Title';

	/**
	 * @var  description what the widget does
	 */
	public $description = 'Widget description';


	/**
	 * @var  unique name of the widget in the configs
	 */
	public $widget_name = NULL;


	/**
	 * @var  easy way to know if the widget is loaded
	 */
	public $loaded = FALSE;


	public function __construct(){}


	/**
	 * gets the fields value form the DB config
	 * @param  string $widget_name 
	 * @param array $widget_data optional
	 * @return boolean              
	 */
	public function load($widget_name,array $widget_data = NULL)
	{

		//search for widget config
		if ($widget_data==NULL OR !is_array($widget_data))
		{
			$widget_data = core::config('widget.'.$widget_name);
			//found and with data!
			if($widget_data!==NULL AND !empty($widget_data) AND $widget_data !== '[]')
			{ 
				$widget_data = json_decode($widget_data, TRUE);
				$widget_data = $widget_data['data'];
				
			}
			else return FALSE;
		}

		//populate the data we got
		$this->widget_name 	= $widget_name;
		$this->data 	   	= $widget_data;
		$this->loaded 		= TRUE;

		return TRUE;

	}

	/**
	 * saves current widget data into the DB config
	 * @return boolean 
	 */
	public function save()
	{
		//stores $data array as json in the config. We need the placeholder?
		$save = array('class'	=> get_class($this),
					  'created'	=> time(),
					  'data'	=> $this->data
					);

		//since was not loaded we assume is new o generate a new name that doesn't exists
		if(!$this->loaded)
			$this->widget_name = $this->gen_name();

		// save widget to DB
   		$conf = new Model_Config();
   		$conf->group_name = 'widget';
   		$conf->config_key = $this->widget_name;
   		$conf->config_value = json_encode($save);

   		try {
   			$conf->save();
   			$this->loaded = TRUE;
   			return TRUE;
   		} 
   		catch (Exception $e) {
  			throw new HTTP_Exception_500();		
   		}

   		return FALSE;
		
	}

	/**
	 * unload the widget
	 * @return void
	 */
	public function unload()
	{
		$this->data 		= array();
		$this->loaded 		= FALSE;
		$this->widget_name 	= NULL;
	}


	/**
	 * renders the widget view with the data
	 * @return string HTML 
	 */		
	public function render()
	{
		if ($this->loaded)
		{
			$this->before();

			//get the view file (check if exists in the theme if not default), and inject the widget
			$out = View::factory(strtolower(get_class($this)),array('widget' => $this));

			$this->after();

			return $out;
		}
		
		return FALSE;
	}

	/**
	 * renders the form view to fill the data and then saves it
	 * @return string html
	 */
	public function form()
	{
		//renders the view with data if widget is loaded or without if it's for new
		
		//if loaded actions: delete, save
		if ($this->loaded)
		{

		}
		else//if new generate unique ID, action save. Update we can not give it a name....
		{
			//$this->widget_name = $this->gen_name();
		}

		//for each field reder html_tag
		$tags = array();
		foreach ($this->fields as $name => $options) 
		{
			$value = (isset($this->data[$name]))?$this->data[$name]:NULL;
			$tags[] = $this->html_tag($name, $options, $value);
		}

		//render view
		return View::factory('oc-panel/pages/widgets/form_widget', array( 'widget' => $this, 
																		  'tags'   => $tags
																		 )
							);

	}

	/**
	 * get the html tag code for a field
	 * @param  string $name input name
	 * @param  array  $options as defined
	 * @param  mixed $value value of the field, optional.
	 * @return string        HTML
	 */
	public function html_tag($name, array $options, $value = NULL)
	{
		$out = FORM::label($name, (isset($options['label']))?$options['label']:$name, array('class'=>'control-label', 'for'=>$name));

		if ($value === NULL)
			$value = (isset($options['default'])) ? $options['default']:NULL;


		$attributes = array('placeholder' => (isset($options['default'])) ? $options['default']:$name, 
					'class'		 => 'input-large', 
					'id' 		=> $name, 
					(isset($options['required']))?'required':''
					);

		switch ($options['display']) 
		{
			case 'select':
				$out.=FORM::select($name, $options['options'],$value , $attributes);
				break;
			case 'textarea':
				$out.=FORM::textarea($name, $value, $attributes);
				break;
			case 'text':
			default:
				$out.=FORM::input($name, $value, $attributes);
				break;
		}

		

		return $out;

	}

	/**
	 * generates a name for this widget
	 * @return string
	 */
	public function gen_name()
	{
		return get_class($this).'_'.time();
	}

	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		// Nothing by default
	}

	/**
	 * Automatically executed after the widget action. Can be used to apply
	 * transformation to the request response, add extra output, and execute
	 * other custom code.
	 *
	 * @return  void
	 */
	public function after()
	{
		// Nothing by default
	}

	/**
	 * Magic methods to set get
	 */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;        
    }

	public function __get($name)
    {
        return (array_key_exists($name, $this->data)) ? $this->data[$name] : NULL;
    }
}