<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Market seettings
 */


class Controller_Panel_Market extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Market'))->set_url(Route::url('oc-panel',array('controller'  => 'market'))));

    }

    public function action_index()
    {
        Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'market','action'=>'theme')));  
    }

    /**
     * theme options/settings
     * @return [view] Renders view with form inputs
     */
    public function action_options()
    {
        // validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
        //$this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Theme Options')));     
        $this->template->title = __('Theme Options');  

        // save only changed values
        if($this->request->post())
        {
            //for each option read the post and store it
            foreach ($_POST as $key => $value) 
            {
                if (isset(Theme::$options[$key]))
                {
                    Theme::$data[$key] = core::post($key);
                }
            }
            
            Theme::save();
            
            Alert::set(Alert::SUCCESS, __('Success, Theme configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'market','action'=>'options')));
        }

        $this->template->content = View::factory('oc-panel/pages/market/options', array('options' => Theme::$options, 'data'=>Theme::$data));
    }

    /**
     * theme selector
     * @return [view] 
     */
    public function action_theme()
    {
        // validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
        //$this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Themes')));  
        $this->template->title = __('Themes');     

        //getting the themes
        $themes = Theme::get_installed_themes();
        
        // @todo future from RSS Theme::get_market_themes();

        // save only changed values
        if($this->request->param('id'))
        {
            Theme::set_theme($this->request->param('id'));
            
            Alert::set(Alert::SUCCESS, __('Success, Appearance configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'market','action'=>'theme')));
        }

        $this->template->content = View::factory('oc-panel/pages/market/theme', array('themes' => $themes, 'selected'=>Theme::$theme));
    }

    /**
     * extra stuff to buy
     * @return [view] 
     */
    public function action_extras()
    {
        // validation active 
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Extras')));  
        $this->template->title = __('Extras');     

        $extras = array();
        
        // @todo future from RSS Theme::get_market_themes();

        $this->template->content = View::factory('oc-panel/pages/market/extras', array('extras' => $extras));
    }

}//end of controller