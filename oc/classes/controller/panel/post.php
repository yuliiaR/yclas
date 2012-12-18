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

		$_cat = $category->find_all();
		$_loc = $location->find_all();

		$filename = NULL;
		//post submited
		if ($this->request->post())
		{
			//form values
			$form = array(	'title' => '',				
							'description'	=> '',
							);
			
			$errors = $form;

			//check to see if the form was submittet
			if (isset($_POST['submit'])){
				//form validation
				$post = new Validation($_POST);


	            $post->pre_filter('trim');
	             
	            //Add rules for contact_name 
	            $post->add_rules('title', 'required', 'standard_text', 'length[2,20]');
	             
	            // //Add rules for contact_email 
	            // $post->add_rules('contact_email', 'required', 'email', 'email_domain');
	             
	            //Add rules for description 
	            $post->add_rules('description', 'required', 'standard_text');

	            //If there were no errors...
            	if($post->validate())
            	{
            		//Load the config file with our email address defaults 
                	$email_config = Kohana::config_load('email');

                	$to = $email_config['default_email'];
            	}
			}  
			
			//recaptcha validation, if recaptcha active
			
			//check account exists
				//if exists send email to activate post
				//if not exists create account and send email to confirm
				
			//save post data
			
			//save images, shrink and move to folder /upload/2012/11/25/pics/
			
		}
		
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/post/new', array('_cat' => $_cat,
																		 '_loc' => $_loc
																		));

		$this->template->content->text = Text::bb2html($this->request->post('description'),TRUE);	
 	}
 	
 } // End Post controller
