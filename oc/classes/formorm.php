<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form specific to use with orm, used in the Auth Crud
 * 
 *
 * @package    OC
 * @category   Helpers
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 * @see https://github.com/colinbm/kohana-formmanager
 */

class FormOrm extends FormManager {


	/**
	 * Constructor
	 * @param string $model model name
	 * @param int $id Primary key of the model. Ignored unless $this->model is set.
	 * @param string $parent_container The parent container for the form elements
	 */
	public function __construct($model,$id = null, $parent_container = null)
	{
		$this->model = $model;
		$element = ORM::factory($this->model, $id);
		$element->form_setup($this);
		//d($this->exclude_fields);
		parent::__construct($element, $parent_container);

	}

	/**
	 * 
	 * sets the protected excluded fields
	 * @param array $fields
	 */
	public function set_sexclude_fields($fields = NULL)
	{
		$this->exclude_fields = $fields;
	}
}