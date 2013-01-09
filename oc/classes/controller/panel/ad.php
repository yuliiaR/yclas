<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Ad extends Auth_Controller {

   	/**
   	 * 
   	 * List all Advertizment
   	 */
	public function action_index()
	{
		//template header
		$this->template->title           	= __('Advertizments');
		$this->template->meta_description	= __('Advertizments');
				
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		//$this->template->scripts['footer'][]= 'js/autogrow-textarea.js';
		$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= 'js/pages/new.js';

		//find all tables 
        $hits = new Model_Visit();
        $hits->find_all();

		$c = new Controller_Ad($this->request,$this->response);// object of listing
        
        $arr_ads = $c->action_list_logic()['ads']; 
       	
       	$arr_hits = array(); // array of hit integers 
        
        //fill array with hit integers 
        foreach ($arr_ads as $key_ads) {
        	// match hits with ad
        	$hits->where('id_ad','=', $key_ads->id_ad)->and_where('id_user', '=', $key_ads->id_user);
        	$count = $hits->count_all(); // count individual hits 
        	$arr = $c->action_list_logic();

        	array_push($arr_hits, $count);
        	array_push($arr, $count);
        }
	    
	    $this->template->content = View::factory('oc-panel/pages/ad', array('res'=> $c->action_list_logic(), 'hits'=>$arr_hits)); // create view, and insert list with data 		
	}

	

	/**
	 * @TODO : add more dynamic, to enable admin to make changes 
	 * One advertizemt : single VIEW
	 */
	public function action_view()
	{
		$ad = ORM::factory('ad', $this->request->param('id'));
		
		if ($ad->loaded())
		{
			$this->template->bind('content', $content);
			$this->template->content = View::factory('oc-panel/pages/single', array('ad'=>$ad));
		}
		else
		{
			//throw 404
			throw new HTTP_Exception_404();
		}
	}

	/**
	 *
	 * Delete advertizment: Delete
	 */
	public function action_delete()
	{
		$this->auto_render = FALSE;
		$this->template = View::factory('js');
		$element = ORM::factory('ad', $this->request->param('id'));
		
		try
		{
			$element->delete();
			//$this->template->content = 'OK';
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		}
		catch (Exception $e)
		{
			throw new HTTP_Exception_500($e->getMessage());
		}
	}

	/**
	 * 
	 * Edit advertizment: Update
	 */
	public function action_update()
	{
		$ad = ORM::factory('ad', $this->request->param('id'));

		if ($ad->loaded())
		{
			$this->template->content = View::factory('oc-panel/pages/edit');
		}
		else
		{
			//throw 404
			throw new HTTP_Exception_404();
		}

	}
	/**
	 * 
	 * Mark adverizment as spam : STATUS = 30
	 */
	public function action_spam()
	{
		$spam_ad = ORM::factory('ad', $this->request->param('id'));

		if ($spam_ad->loaded())
		{
			if ($spam_ad->status != 30)
			{
				$spam_ad->status = 30;
				
				try
				{
					$spam_ad->save();
					Alert::set(Alert::SUCCESS, __('Success, advertizment is marked as spam'));
					Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));

				}
					catch (Exception $e)
				{
					throw new HTTP_Exception_500($e->getMessage());
				}
			}
			else
			{				
				Alert::set(Alert::ALERT, __('Warning, advertizment is already marked as spam'));
				Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
			} 
		}
		else
		{
			//throw 404
			throw new HTTP_Exception_404();
		}
	}

	/**
	 * 
	 * Mark adverizment as deactiavted : STATUS = 50
	 */
	public function action_deactivate()
	{
		$deact_ad = ORM::factory('ad', $this->request->param('id'));

		if ($deact_ad->loaded())
		{
			if ($deact_ad->status != 50)
			{
				$deact_ad->status = 50;
				
				try
				{
					$deact_ad->save();
					Alert::set(Alert::SUCCESS, __('Success, advertizment is deactivated'));
					Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));

				}
					catch (Exception $e)
				{
					throw new HTTP_Exception_500($e->getMessage());
				}
			}
			else
			{				
				Alert::set(Alert::ALERT, __("Warning, advertizment is already marked as 'deactivated'"));
				Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
			} 
		}
		else
		{
			//throw 404
			throw new HTTP_Exception_404();
		}
	}

}
