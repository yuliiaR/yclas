<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller {

	/**
	 * 
	 * Display single page
	 * @throws HTTP_Exception_404
	 */
	public function action_view()
	{
		$seotitle = $this->request->param('seotitle',NULL);
		if ($seotitle!==NULL)
		{
			$page = new Model_Page();
			$page->where('seotitle','=', $seotitle)
				 ->limit(1)->cached()->find();
			
			if ($page->loaded())
			{
				$this->template->title            = $page->title;
				
				$this->template->meta_description = $page->description;//@todo phpseo
				
				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/page',array('page'=>$page));
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
	

} // End Post controller
