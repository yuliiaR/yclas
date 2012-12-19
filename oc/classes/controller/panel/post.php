 <?php defined('SYSPATH') or die('No direct script access.');

 class Controller_Panel_Post extends Controller {


 	public function action_index(){

 		//template header
		$this->template->title           	= __('Publish new advertisement');
		$this->template->meta_description	= __('Publish new advertisement');
				
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		//$this->template->scripts['footer'][]= 'js/autogrow-textarea.js';
		$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= 'js/pages/new.js';
		
		$category = new Model_Category();
		$location = new Model_Location();
		$user = new Model_User();

		

		$_cat = $category->find_all();
		$_loc = $location->find_all();

		$this->template->content = View::factory('pages/post/new', array('_cat'		 	=> $_cat,
																		 '_loc' 		=> $_loc,
																		 'user' 		=> $user,
																		 
																		));
		//post submited
		//if ($this->request->post())
		//{
		$title = $this->request->post('title');
		$description = $this->request->post('description');

		$_new_post = new Model_Post();
		$_new_post->title = $title;
		$_new_post->description = $description;
		$_new_post->id_user = 2;
		$_new_post->id_category = 2;
		$_new_post->id_location = 1;

		
				$user->save();
			
			//recaptcha validation, if recaptcha active
			
			//check account exists
				//if exists send email to activate post
				//if not exists create account and send email to confirm
				
			//save post data
			
			//save images, shrink and move to folder /upload/2012/11/25/pics/
			
		//}
		
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/post/new', array('_cat'		 	=> $_cat,
																		 '_loc' 		=> $_loc,
																		 'user' 		=> $user,
																		 
																		));

		$this->template->content->text = Text::bb2html($this->request->post('description'),TRUE);	
 	}
 	
 } // End Post controller
