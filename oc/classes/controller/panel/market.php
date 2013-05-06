<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Market seettings
 */


class Controller_Panel_Market extends Auth_Controller {


    public function action_index()
    {
        
        // validation active 
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Market')));  
        $this->template->title = __('Market');     

        $extras = array();
        
        // @todo future from json

        $this->template->content = View::factory('oc-panel/pages/market', array('extras' => $extras));
    }



}//end of controller