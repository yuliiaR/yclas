<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * 
 * Display user profile
 * @throws HTTP_Exception_404
 */
class Controller_User extends Controller {
		
	public function action_index()
	{
		$seoname = $this->request->param('seoname',NULL);
		if ($seoname!==NULL)
		{
			$user = new Model_User();
			$user->where('seoname','=', $seoname)
				 ->limit(1)->cached()->find();
			
			if ($user->loaded())
			{
				$this->template->title = $user->name;
				
				//$this->template->meta_description = $user->name;//@todo phpseo
				
				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/userprofile',array('user'=>$user));
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
		$seoname = $this->request->param('seoname',NULL);
		if ($seoname!==NULL)
		{
			$user = new Model_User();
			$user->where('seoname','=', $seoname)
				 ->limit(1)->cached()->find();
			
			if ($user->loaded())
			{
				$this->template->title = $user->name;
				//$this->template->meta_description = $user->name;//@todo phpseo
				
				$captcha_show = core::config('formconfig.captcha-captcha'); // show captcha
				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/useredit',array('user'=>$user, 'captcha_show'=>$captcha_show));

				if($this->request->post())
				{
					if($captcha_show === 'FALSE' || captcha::check('contact'))
					{
						$user->name = $this->request->post('name');
						$user->email = $this->request->post('email');
						$user->seoname = URL::title($this->request->post('name'), '-', FALSE);
						// $user->password2 = $this->request->post('password2');
						
						$password1 = $this->request->post('password1');
						if(!empty($password1))
						{
							if($this->request->post('password1') === $this->request->post('password2'))
							{
								$user->password = $this->request->post('password1');
							}
							else
							{
								Alert::set(Alert::ERROR, __('New password is invalid, or they do not match! Please try again.'));
								$this->request->redirect(Route::url('profile', array('action'=>'edit','seoname'=>$seoname)));
							}
						} 

						try {
							$user->save();
							Alert::set(Alert::SUCCESS, __('You have successfuly chaged your data'));
							$this->request->redirect(Route::url('profile', array('seoname'=>URL::title($this->request->post('name'), '-', FALSE))));
							
						} catch (Exception $e) {
							//throw 500
							throw new HTTP_Exception_500();
						}
					}
				}

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
}// End Userprofile Controller

