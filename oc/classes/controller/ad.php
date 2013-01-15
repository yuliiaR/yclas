<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ad extends Controller {
	
	/**
	 * 
	 * Display single ad
	 * @throws HTTP_Exception_404
	 */
	public function action_view()
	{
		$seotitle = $this->request->param('seotitle',NULL);
		
		if ($seotitle!==NULL)
		{
			$ad = new Model_Ad();
			$ad->where('seotitle','=', $seotitle)
				 ->limit(1)->find();
			
			if ($ad->loaded())
			{
			
				$do_hit = $ad->count_ad_hit($ad->id_ad, $ad->id_user); // hits counter

				//count how many matches are found 
		        $hits = new Model_Visit();
		        $hits->find_all();
		        $hits->where('id_ad','=', $ad->id_ad)->and_where('id_user', '=', $ad->id_user); 

				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/post/single',array('ad'=>$ad, 'hits'=>$hits->count_all()));

			}
			//not found in DB
			else
			{
				//throw 404
				throw new HTTP_Exception_404();
			}
			
		}
		else//this will never happen
		{
			//throw 404
			throw new HTTP_Exception_404();
		}
	}
	
	public function action_edit()
	{
		$seotitle = $this->request->param('seotitle',NULL);
		
		if ($seotitle!==NULL)
		{
			$ad = new Model_Ad();
			$ad->where('seotitle','=', $seotitle)
				 ->limit(1)->find();
			
			if ($ad->loaded())
			{

				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/post/single',array('ad'=>$ad));
			}
			//not found in DB
			else
			{
				//throw 404
				throw new HTTP_Exception_404();
			}
			
		}
		else//this will never happen
		{
			//throw 404
			throw new HTTP_Exception_404();
		}
		
	}
	
	
	/**
	 * Serp of ads
	 */
	public function action_index()
	{ 
		$this->template->bind('content', $content);
	    $this->template->content = View::factory('pages/post/listing',$this->action_list_logic());
 	}

	public function action_list_logic()
	{
		$ads = new Model_Ad();

		$ads->where('ad.status', '=', Model_Ad::STATUS_PUBLISHED);
		$ads->find_all();
		$_search_ad = ORM::factory('ad');
		$res_count = $ads->count_all();
		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> 5
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                 
    	    ));
    	    $ads = $ads->order_by('created','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();
			}
		else
		{
			//trow 404 Exception
			throw new HTTP_Exception_404();
		}
		return array('ads'=>$ads,'pagination'=>$pagination);
	}
	

	
	public function action_search()
	{
		echo strlen($this->request->param('search',NULL));
		
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/post/search');	
	}
}// End ad controller
