<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Fields extends Auth_Controller {

    
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Custom Fields'))->set_url(Route::url('oc-panel',array('controller'  => 'fields'))));

    }

	public function action_index()
	{
     
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Custom Fields for Advertisements')));
		$this->template->title = __('Custom Fields');

        //retrieve fields


		$this->template->content = View::factory('oc-panel/pages/fields/index',array());
	}
    

    public function action_new()
    {
     
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New')));
        $this->template->title = __('New Custom Field for Advertisement');


        if ($_POST)
        {
            $name   = Core::post('name');

            $field = new Model_Field();

            try {

                if ($field->create($name,Core::post('type'),Core::post('values')))
                {
                    Cache::instance()->delete_all();
                    Theme::delete_minified();

                    Alert::set(Alert::SUCCESS,__('Field created '.$name));
                    Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'fields','action'=>'index')));  
                }
                else
                    Alert::set(Alert::ERROR,__('Field already exists '.$name));

                

            } catch (Exception $e) {
                throw new HTTP_Exception_500();     
            }
        }

        

        


        $this->template->content = View::factory('oc-panel/pages/fields/new',array());
    }


    public function action_delete()
    {
        //get name of the param, get the name of the custom fields, deletes from config array and alters table
        $this->auto_render = FALSE;
        $name   = $this->request->param('id');
        $field  = new Model_Field();

        try {

                if ($field->delete($name))
                {
                    Cache::instance()->delete_all();
                    Theme::delete_minified();
                    Alert::set(Alert::SUCCESS,__('Field deleted '.$name));
                }
                else
                    Alert::set(Alert::ERROR,__('Field does not exists '.$name));

                $this->request->redirect(Route::url('oc-panel', array('controller'=>'fields', 'action'=>'index')));

        } catch (Exception $e) {
            //throw 500
            throw new HTTP_Exception_500();     
        }
        
        
    }

	

}
