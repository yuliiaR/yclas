<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Sitemap extends Auth_Controller {


	public function action_index()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(ucfirst(__('Sitemap'))));
		
		$this->template->title = __('Sitemap');

		
		// all sitemap config values
        $sitemapconfig = new Model_Config();
        $config = $sitemapconfig->where('group_name', '=', 'sitemap')->find_all();
      
        // save only changed values
        if($this->request->post())
        {
        	foreach ($config as $c) 
            {
                $config_res = $this->request->post($c->config_key); 
                
                if($config_res != $c->config_value)
                {
                    $c->config_value = $config_res;
                    try {
                        $c->save();
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }
            // Cache::instance()->delete_all();
            Alert::set(Alert::SUCCESS, __('Success, Sitemap Configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'sitemap','action'=>'index')));
        }

		//force regenerate sitemap
		if (Core::get('force')==1)
			Alert::set(Alert::SUCCESS, Sitemap::generate(TRUE));

		$this->template->content = View::factory('oc-panel/sitemap');
	}



}
