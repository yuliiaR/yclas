<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Category extends Auth_Crud {

    
	
	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('name','order','price', 'id_category', 'id_category_parent');
	
	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'category';	


    public function action_index($view = NULL)
    {
        Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'category','action'=>'dashboard')));  
    }

    public function action_dashboard()
    {

        //template header
        $this->template->title  = __('Categories');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Categories')));

        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/categories.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';


        list($cats,$order)  = Model_Category::get_all();

        $this->template->content = View::factory('oc-panel/pages/categories',array('cats' => $cats,'order'=>$order));

    }



    public function action_saveorder()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        $cat = new Model_Category(core::get('id_category'));

        if ($cat->loaded())
        {
            $cat->id_category_parent = core::get('id_category_parent');
            $cat->order              = core::get('order');
            $cat->parent_deep        = core::get('deep');
            $cat->save();
            $this->template->content = __('Saved');
        }
        else
            $this->template->content = __('Error');
        

    }

}
