<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Fields extends Auth_Controller {

    
	public function action_index()
	{
     
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Custom Fields for Advertisements')));
		$this->template->title = 'Custom Fields';

        //retrieve fields


		$this->template->content = View::factory('oc-panel/pages/fields/index',array());
	}
    

    public function action_new()
    {
     
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New Custom Field for Advertisement')));
        $this->template->title = 'New Custom Field';

        $field = new Model_Field();
        $field->create('name','string',256,'default');

        if ($_POST)
        {

        }


        $this->template->content = View::factory('oc-panel/pages/fields/index',array());
    }


    public function action_delete()
    {
        //get name of the param, get the name of the custom fields, deletes from config array and alters table

        $field = new Model_Field();
        $field->delete('name');
    }

	

}
