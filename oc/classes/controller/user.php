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

				$ads = new Model_Ad();
				$ads = $ads->where('id_user', '=', $user->id_user)->find_all();
				
				$category = new Model_Category();
				$category = $category->find_all();
				
		
				if($ads->count() !== 0)
				{
					foreach ($ads as $value) 
					{
						foreach ($category as $key) 
						{
							if($key->id_category == $value->id_category)
							{
								$cat = $key->name;
							}
						}
					
					$profile_ads[] = array('title'		=>$value->title, 
										   'category'	=>$cat,
										   'description'=>$value->description,
										   'seotitle'	=>$value->seotitle,
										   'id_ad'		=>$value->id_ad,
										   'id_user'	=>$value->id_user); 
					}	
				}
				else $profile_ads = NULL;

				$this->template->content = View::factory('pages/userprofile',array('user'=>$user, 'profile_ads'=>$profile_ads));
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

