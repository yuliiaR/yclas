<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Post extends ORM {


	/**
	 * 
	 * Display single post
	 * @throws HTTP_Exception_404
	 */
	public function action_view()
	{
		$seotitle = $this->request->param('seotitle',NULL);
		
		if ($seotitle!==NULL)
		{
			$post = new Model_Post();
			$post->where('seotitle','=', $seotitle)
				 ->limit(1)->find();
			
			if ($post->loaded())
			{

				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/post/single',array('post'=>$post));
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
	 * 
	 * new post
	 */
	public function action_newpost()
	{
		//template header
		$this->template->title           	= __('Publish new advertisement');
		$this->template->meta_description	= __('Publish new advertisement');
				
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		//$this->template->scripts['footer'][]= 'js/autogrow-textarea.js';
		$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= 'js/pages/new.js';
		
		
		//post submited
		if ($this->request->post())
		{
			//form validation
			//recaptcha validation, if recaptcha active
			
			//check account exists
				//if exists send email to activate post
				//if not exists create account and send email to confirm
				
			//save post data
			
			//save images, shrink and move to folder /upload/2012/11/25/pics/
			
		}
		
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/post/new');
		$this->template->content->text = Text::bb2html($this->request->post('description'),TRUE);
	}

} // End Post controller
