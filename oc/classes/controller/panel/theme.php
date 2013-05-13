<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Market seettings
 */


class Controller_Panel_Theme extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Themes'))->set_url(Route::url('oc-panel',array('controller'  => 'theme'))));

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
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme','action'=>'options')));
        }

        $this->template->content = View::factory('oc-panel/pages/themes/options', array('options' => Theme::$options, 'data'=>Theme::$data));
    }

    /**
     * theme selector
     * @return [view] 
     */
    public function action_index()
    {
        // validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
        //$this->template->scripts['footer'][]= '/js/oc-panel/settings.js';
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Themes')));  
        $this->template->title = __('Themes');     

        //getting the themes
        $themes = Theme::get_installed_themes();

        $mobile_themes = Theme::get_installed_themes(TRUE);

        //getting themes from market
        $market = array();
        $json = Core::get_market();
        foreach ($json as $theme) 
        {

            if ($theme['type'] == 'theme' AND !in_array(strtolower($theme['title']), array_keys($themes)) )
                $market[] = $theme;
        }


        // save only changed values
        if($this->request->param('id'))
        {
            Theme::set_theme($this->request->param('id'));
            
            Alert::set(Alert::SUCCESS, __('Success, Appearance configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme','action'=>'index')));
        }

        $this->template->content = View::factory('oc-panel/pages/themes/theme', array('market' => $market,
                                                                                    'themes' => $themes, 
                                                                                    'mobile_themes' => $mobile_themes,
                                                                                    'selected'=>Theme::get_theme_info(Theme::$theme)));
    }


    /**
     * mobile theme selector
     * @return [view] 
     */
    public function action_mobile()
    {

        // save only changed values
        if($this->request->param('id'))
        {
            Theme::set_mobile_theme($this->request->param('id'));
            
            Alert::set(Alert::SUCCESS, __('Success, Mobile Theme updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'theme','action'=>'index')));
        }

       
    }

}//end of controller