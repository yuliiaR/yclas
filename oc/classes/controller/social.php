<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Social extends Controller {
	
	public function action_loggin()
	{
		require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Auth','php');
		require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Endpoint','php');
			
		$config = json_decode(core::config('social.config'),TRUE);
		
		if ($this->request->query('hauth_start') OR $this->request->query('hauth_done'))
		{
			try 
			{
				Hybrid_Endpoint::process($this->request->query());
			} 
			catch (Exception $e) 
			{
				Alert::set(Alert::ERROR, $e->getMessage());
				$this->request->redirect(Route::url('oc-panel', array('controller'=>'home','action'=>'index')));
			}
				
		}
		else
		{ 
			$provider_name = $this->request->param('id');
	 
			try
			{
				// initialize Hybrid_Auth with a given file
				$hybridauth = new Hybrid_Auth( $config );
	 
				// try to authenticate with the selected provider
				$adapter = $hybridauth->authenticate( $provider_name );

				if ($hybridauth->isConnectedWith($provider_name)) 
				{
					var_dump($adapter->getUserProfile());
				}
			}
			catch( Exception $e )
			{
				Alert::set(Alert::ERROR, __('Error: please try again!')." ".$e->getMessage());
				$this->request->redirect(Route::url('oc-panel', array('controller'=>'home','action'=>'index')));
			}
		} 
	}
}	