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
		
		//find all, for populating form select fields 
		$_cat = $category->find_all();
		$_loc = $location->find_all();

		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/post/new', array('_cat'		=> $_cat,
																		 '_loc' 	=> $_loc,));
		
		// post attributes
		$usr = Auth::instance()->get_user()->id_user; // returns and error if user not loged in !!! check that 
		$title 			= 	$this->request->post('title');
		$seotitle 		= 	$this->request->post('title'); // need to do some validation and checking with seotitle !!!
		$cat 			= 	$this->request->post('category');
		$loc 			= 	$this->request->post('location');
		$description 	= 	$this->request->post('description');
		$price 			= 	$this->request->post('price');
		$address 		= 	$this->request->post('address');
		$phone 			= 	$this->request->post('phone');

		////////////////
		// do user check 
		// TO DO ...
		/////////////// 
		
		$bla = ORM::factory('post');
		$bla->where('title', '=', $title)->find();
		
		// check existance of post element
		if ($bla->loaded()){
			echo "ERROR";
		}
		else if($this->request->post()) //post submition  
		{
		
		
		//insert data
		$bla->title 		= $title;
		$bla->id_location 	= $loc;
		$bla->id_category 	= $cat;
		$bla->id_user 		= $usr;
		$bla->description 	= $description;
		$bla->type 			= '0';
		$bla->seotitle 		= $title.' '.$bla->count_all();	
		$bla->status 		= '1';// check this, maybe it needs to dynamic
		$bla->price 		= $price; // this field is missing in html !!!
		$bla->adress 		= $address;
		$bla->phone			= $phone; 
		
		var_dump($bla->count_all());
			if (!Auth::instance()->logged_in()) // this part is for users that are not logged 
			{
				// $name = $this->request->post('name');
				// $email = $this->request->post('email');
				// $phone = $this->request->post('phone');
				
				// if (Auth::instance()->get_user()->name == $name && Auth::instance()->get_user()->email == $email)
				// {
				// 	$bla->name = Auth::instance()->get_user()->name;
				// 	$bla->email = Auth::instance()->get_user()->email;
				// 	$bla->phone = $phone; // this is called if user is loged in, but there is no "phone" columne in user table !!!
				// }
				// else
				// {
				// 	echo "User name ".$name." or email ".$email." is not valid";
				// 	echo "Database cannot find a mach ";
				// }

				
			}	
				// // image upload
				$error_message = NULL;
        		$filename = NULL;
        		
        		if (isset($_FILES['image1']))
            	{
                	$filename = $this->_save_image($_FILES['image1']);
            	}
            	if ( ! $filename)
		        {
		            $error_message = 'There was a problem while uploading the image.
		                Make sure it is uploaded and must be JPG/PNG/GIF file.';

		                echo $error_message;
		        }
		        //$this->template->content->uploaded_file = $filename;
		        //$this->template->content->error_message = $error_message;
		try
			{
				$bla->save();
			}
			catch (ORM_Validation_Exception $e)
			{
				echo $e;
				Form::errors($content->errors);
			}
			catch (Exception $e)
			{
				throw new HTTP_Exception_500($e->getMessage());
			}

			
			//recaptcha validation, if recaptcha active
			
			//check account exists
				//if exists send email to activate post
				//if not exists create account and send email to confirm
				
			//save post data
			
			//save images, shrink and move to folder /upload/2012/11/25/pics/
			
		}
		
		
		

		$this->template->content->text = Text::bb2html($this->request->post('description'),TRUE);	
 	}

 	public function _save_image($image)
 	{
 		if (
            ! Upload::valid($image) OR
            ! Upload::not_empty($image) OR
            ! Upload::type($image, array('jpg', 'jpeg', 'png', 'gif')))
        {
            return FALSE;
 		}

 		$directory = DOCROOT.'upload/';

 		if ($file = Upload::save($image, NULL, $directory))
        {
            $filename = strtolower(Text::random('alnum', 20)).'.jpg';
 
            Image::factory($file)
                ->resize(200, 200, Image::AUTO)
                ->save($directory.$filename);
 
            // Delete the temporary file
            unlink($file);
 
            return $filename;
        }
 
        return FALSE;
    }
 	
 } // End Post controller
