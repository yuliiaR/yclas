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



    public function action_dashboard()
    {

        //template header
        $this->template->title  = __('Categories');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Categories')));

        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/categories.js';


        list($cats,$order)  = Model_Category::get_all();

        $this->template->content = View::factory('oc-panel/pages/categories',array('cats' => $cats,'order'=>$order));

    }



    public function action_saveorder()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        DB::delete('config')->where('group_name','=','placeholder')->execute();

        //for each placeholder
        foreach ($_GET as $placeholder => $widgets) 
        {
            if (!is_array($widgets))
                $widgets = array();

            //insert in DB palceholders
            $confp = new Model_Config();
            $confp->group_name = 'placeholder';
            $confp->config_key = $placeholder;
            $confp->config_value = json_encode($widgets); 
            $confp->save();

            //edit each widget change placeholder
            foreach ($widgets as $wname) 
            {
                $w = Widget::factory($wname);
                $w->placeholder = $placeholder;
                $w->save();
            }
            
            
        }

        $this->template->content = __('Saved');

    }

}
