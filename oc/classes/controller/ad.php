<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ad extends Controller {
	

	/**
	 * Publis all adver.-s without filter
	 */
	public function action_listing()
	{ 
		if(Theme::get('infinite_scroll'))
		{
			$this->template->scripts['footer'][] = 'http://cdn.jsdelivr.net/jquery.infinitescroll/2.0b2/jquery.infinitescroll.js';
			$this->template->scripts['footer'][] = 'js/listing.js';
		}
        $this->template->scripts['footer'][]= 'js/jquery.toolbar.js';
		$this->template->scripts['footer'][] = 'js/sort.js';
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		
        
        /**
         * we get the model of category and location from controller to filter and generate urls titles etc...
         */
        
        $location = NULL;
        $location_parent = NULL;
        $location_name = NULL;

        if (Model_Location::current()->loaded())
        {
        	$location = Model_Location::current();
            if($location->id_location != 1)
            $location_name = $location->name;
        
            //adding the location parent
            if ($location->id_location_parent!=1 AND $location->parent->loaded())
                $location_parent = $location->parent;
        }  
        

        $category = NULL;
        $category_parent = NULL;
        $category_name = NULL;

        if (Model_Category::current()->loaded())
        {
            $category = Model_Category::current();
            if($category->id_category != 1)
                $category_name = $category->name;
            //adding the category parent
            if ($category->id_category_parent!=1 AND $category->parent->loaded())
                $category_parent = $category->parent;
        }
        
        //base title
        if ($category!==NULL)
            $this->template->title = $category_name;
        else
            $this->template->title = __('all');

        //adding location titles and breadcrumbs
        if ($location!==NULL)
        {
            $this->template->title .= ' - '.$location->name;

            if ($location_parent!==NULL)
            {
                $this->template->title .=' ('.$location_parent->name.')';
                Breadcrumbs::add(Breadcrumb::factory()->set_title($location_parent->name)->set_url(Route::url('list', array('location'=>$location_parent->seoname))));
            }

            Breadcrumbs::add(Breadcrumb::factory()->set_title($location->name)->set_url(Route::url('list', array('location'=>$location->seoname))));
                
            if ($category_parent!==NULL)
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                    ->set_url(Route::url('list', array('category'=>$category_parent->seoname,'location'=>$location->seoname))));
            
            if ($category!==NULL)
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                    ->set_url(Route::url('list', array('category'=>$category->seoname,'location'=>$location->seoname))));
        }
        else
        {
            if ($category_parent!==NULL)
            {
                $this->template->title .=' ('.$category_parent->name.')';
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                    ->set_url(Route::url('list', array('category'=>$category_parent->seoname))));
            }
                
            
            if ($category!==NULL)
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                    ->set_url(Route::url('list', array('category'=>$category->seoname))));
        }


    

        $data = $this->list_logic($category, $location);
   		
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/ad/listing',$data);
 	}

    /**
     * gets data to the view and filters the ads
     * @param  Model_Category $category 
     * @param  Model_Location $location
     * @return array           
     */
	public function list_logic($category = NULL, $location = NULL)
	{

		//user recognition 
		$user = (Auth::instance()->get_user() == NULL) ? NULL : Auth::instance()->get_user();

		$ads = new Model_Ad();

		//filter by category or location
        if ($category!==NULL)
        {
            $ads->where('id_category', 'in', $category->get_siblings_ids());

            //category image
            if(file_exists(DOCROOT.'images/categories/'.$category->seoname.'.png'))
                    Controller::$image = URL::base().'images/categories/'.$category->seoname.'.png';
        }

        if ($location!==NULL)
        {
            $ads->where('id_location', 'in', $location->get_siblings_ids());
        }

		//only published ads
        $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED);
		

        //if ad have passed expiration time dont show 
        if(core::config('advertisement.expire_date') > 0)
        {
            $ads->where(DB::expr('DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY)'), '>', Date::unix2mysql());
        }
        
    
        // featured ads
        $featured = NULL; 
        if(Theme::get('listing_slider') == 2)
        {
                $featured = clone $ads;
                $featured = $featured->where('featured', '>=', Date::unix2mysql())
                                ->order_by('featured','desc')
                                ->limit(Theme::get('num_home_latest_ads', 4))
                                ->find_all();
        }
        //random featured
        elseif(Theme::get('listing_slider') == 3)
        {
                $featured = clone $ads;
                $featured = $featured->where('featured', '>=', Date::unix2mysql())
                                ->order_by(DB::expr('RAND()'))
                                ->limit(Theme::get('num_home_latest_ads', 4))
                                ->find_all();
        }

        $res_count = clone $ads;
		$res_count = $res_count->count_all();

		// check if there are some advet.-s
		if ($res_count > 0)
		{
   
       		// pagination module
       		$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> core::config('general.advertisements_per_page'),
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                    'category' 			=> ($category!==NULL)?$category->seoname:NULL,
                    'location'			=> ($location!==NULL)?$location->seoname:NULL, 
    	    ));
    	   
     	    Breadcrumbs::add(Breadcrumb::factory()->set_title(__("Page ").$pagination->current_page));

            /**
             * order depending on the sort parameter
             */
            switch (core::request('sort',core::config('general.sort_by'))) 
            {
                //title z->a
                case 'title-asc':
                    $ads->order_by('title','asc')->order_by('published','desc');
                    break;
                //title a->z
                case 'title-desc':
                    $ads->order_by('title','desc')->order_by('published','desc');
                    break;
                //cheaper first
                case 'price-asc':
                    $ads->order_by('price','asc')->order_by('published','desc');
                    break;
                //expensive first
                case 'price-desc':
                    $ads->order_by('price','desc')->order_by('published','desc');
                    break;
                //featured
                case 'featured':
                    $ads->order_by('featured','desc')->order_by('published','desc');
                    break;
                //oldest first
                case 'published-asc':
                    $ads->order_by('published','asc');
                    break;
                //newest first
                case 'published-desc':
                default:
                    $ads->order_by('published','desc');
                    break;
            }


     	    //we sort all ads with few parameters
       		$ads = $ads ->limit($pagination->items_per_page)
        	            ->offset($pagination->offset)
        	            ->find_all();
		}
		else
		{
			// array of categories sorted for view
			return array('ads'			=> NULL,
						 'pagination'	=> NULL, 
						  'user'        => $user, 
                         'category'     => $category,
                         'location'     => $location,
                         'featured'		=> NULL);
		}
		
		// array of categories sorted for view
		return array('ads'			=> $ads,
					 'pagination'	=> $pagination, 
					 'user'			=> $user, 
					 'category'		=> $category,
					 'location'		=> $location,
					 'featured'		=> $featured);
	}

	/**
	 * 
	 * Display single advert. 
	 * @throws HTTP_Exception_404
	 * 
	 */
	public function action_view()
	{
		$seotitle = $this->request->param('seotitle',NULL);
		
		if ($seotitle!==NULL)
		{
			$ad = new Model_Ad();
			$ad->where('seotitle','=', $seotitle)
                ->where('status','!=',Model_Ad::STATUS_SPAM)
				->limit(1)->cached()->find();

			if ($ad->loaded())
			{
                Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));

                $location = NULL;
                $location_parent = NULL;
                if ($ad->location->loaded() AND $ad->id_location!=1)
                {
                    $location = $ad->location;
                    //adding the location parent
                    if ($location->id_location_parent!=1 AND $location->parent->loaded())
                        $location_parent = $location->parent;
                }  
                

                $category = NULL;
                $category_parent = NULL;
                if ($ad->category->loaded())
                {
                    $category = $ad->category;
                    //adding the category parent
                    if ($category->id_category_parent!=1 AND $category->parent->loaded())
                        $category_parent = $category->parent;

                }

                //base category  title
                if ($category!==NULL)
                    $this->template->title = $category->name;
                else
                    $this->template->title = '';

                //adding location titles and breadcrumbs
                if ($location!==NULL)
                {
                    $this->template->title .= ' - '.$location->name;

                    if ($location_parent!==NULL)
                    {
                        $this->template->title .=' ('.$location_parent->name.')';
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($location_parent->name)->set_url(Route::url('list', array('location'=>$location_parent->seoname))));
                    }

                    Breadcrumbs::add(Breadcrumb::factory()->set_title($location->name)->set_url(Route::url('list', array('location'=>$location->seoname))));
                        
                    if ($category_parent!==NULL)
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                            ->set_url(Route::url('list', array('category'=>$category_parent->seoname,'location'=>$location->seoname))));
                    
                    if ($category!==NULL)
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                            ->set_url(Route::url('list', array('category'=>$category->seoname,'location'=>$location->seoname))));
                }
                else
                {
                    if ($category_parent!==NULL)
                    {
                        $this->template->title .=' ('.$category_parent->name.')';
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                            ->set_url(Route::url('list', array('category'=>$category_parent->seoname))));
                    }
                        
                    
                    if ($category!==NULL)
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                            ->set_url(Route::url('list', array('category'=>$category->seoname))));
                }



                $this->template->title = $ad->title.' - '. $this->template->title;
				
				Breadcrumbs::add(Breadcrumb::factory()->set_title($ad->title));   	

				
                $this->template->meta_description = text::removebbcode($ad->description);

				$permission = TRUE; //permission to add hit to advert and give access rights. 
				$auth_user = Auth::instance();
                if(!$auth_user->logged_in() OR 
					($auth_user->get_user()->id_user != $ad->id_user AND ($auth_user->get_user()->id_role != Model_Role::ROLE_ADMIN AND $auth_user->get_user()->id_role != Model_Role::ROLE_MODERATOR)) OR 
					($auth_user->get_user()->id_role != Model_Role::ROLE_ADMIN AND $auth_user->get_user()->id_role != Model_Role::ROLE_MODERATOR))
				{	

					$permission = FALSE;
					$user = NULL;
				} 
                else 
                    $user = $auth_user->get_user()->id_user;

                $hits = $ad->count_ad_hit();				

				$captcha_show = core::config('advertisement.captcha');	
				
				$cf_config = json_decode(core::config('advertisement.fields'));

				$active_custom_fields = $ad->custom_columns();

				// Custom fields to display
				$ad_fields = array();
				foreach ($active_custom_fields as $name => $value) {
					$real_name = str_replace("cf_", "", $value['parameters']['column_name']);
					
					if(isset($value['value']))//value is set 
					{	
						if($cf_config->$real_name->type == 'checkbox') // checkbox is TRUE or FALSE
						{
							if(isset($value['value']) AND $value['value'])
								$ad_fields[$cf_config->$real_name->label] = $value['value'];
						}
						elseif($cf_config->$real_name->type == 'radio') // Radio have list of choices, but is saved as int in DB
							$ad_fields[$cf_config->$real_name->label] = $cf_config->$real_name->values[$value['value']-1];
						elseif($cf_config->$real_name->type == 'date')
                            $ad_fields[$cf_config->$real_name->label] = Date::format($value['value'], core::config('general.date_format'));
                        else
							$ad_fields[$cf_config->$real_name->label] = $value['value'];

						//admin_privilege can be seen only by admin, so we check if its set / and is admin
						if(isset($cf_config->$real_name->admin_privilege) AND $cf_config->$real_name->admin_privilege)
							if(!$auth_user->logged_in() OR !$auth_user->get_user()->id_role == Model_Role::ROLE_ADMIN)
								if(isset($ad_fields[$cf_config->$real_name->label]))
									unset($ad_fields[$cf_config->$real_name->label]);
					}
					
				}

                // sorting by json
                $ad_custom_vals = array();
                if(isset($cf_config))
                {
                    foreach ($cf_config as $name => $value) 
                    {
                        if(isset($ad_fields[$value->label]))
                            $ad_custom_vals[$value->label] = $ad_fields[$value->label];
                        
                        if($cf_config->$name->type == 'checkbox' AND isset($ad_fields[$value->label]) AND $ad_fields[$value->label])
                            $ad_custom_vals[$value->label] = NULL;
                    }
                }
                    

                if($ad->get_first_image() !== NULL)
                    Controller::$image = $ad->get_first_image();


				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/ad/single',array('ad'				=>$ad,
																				   'permission'		=>$permission, 
																				   'hits'			=>$hits, 
																				   'captcha_show'	=>$captcha_show,
																				   'user'			=>$user,
																				   'cf_list'		=>$ad_custom_vals
																				   ));

			}
			//not found in DB
			else
			{
				//throw 404
				throw HTTP_Exception::factory(404,__('Page not found'));
			}
			
		}
		else//this will never happen
		{
			//throw 404
			throw HTTP_Exception::factory(404,__('Page not found'));
		}
	}
	
	/**
	 * [action_to_top] [pay to go on top, and make order]
	 *
	 */
	public function action_to_top()
	{
        //check pay to go top is enabled
        if(core::config('payment.to_top') == FALSE)
            throw HTTP_Exception::factory(404,__('Page not found'));
        
        $id_product = Model_Order::PRODUCT_TO_TOP;

        //check ad exists
        $id_ad  = $this->request->param('id');
        $ad     = new Model_Ad($id_ad);
        if ($ad->loaded())
        {
            //case when payment is set to 0, it goes to top without payment, no generating order
            if(core::config('payment.pay_to_go_on_top') <= 0)
            {
                $ad->published = Date::unix2mysql();
                try {
                    $ad->save();
                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }

                $this->redirect(Route::url('list')); 
            }

            $amount     = core::config('payment.pay_to_go_on_top');
            $currency   = core::config('payment.paypal_currency');
           
            $order = Model_Order::new_order($ad, $ad->user, $id_product, $amount, $currency);

            // redirect to payment
            $this->redirect(Route::url('default', array('controller' =>'ad','action'=>'checkout' ,'id' => $order->id_order)));
        }
        else
            throw HTTP_Exception::factory(404,__('Page not found'));
	}

    /**
     * [action_to_featured] [pay to go in featured]
     *
     */
    public function action_to_featured()
    {
        //check pay to featured top is enabled
        if(core::config('payment.to_featured') == FALSE)
            throw HTTP_Exception::factory(404,__('Page not found'));
        
        $id_product = Model_Order::PRODUCT_TO_FEATURED;

        //check ad exists
        $id_ad  = $this->request->param('id');
        $ad     = new Model_Ad($id_ad);
        if ($ad->loaded())
        {
            //case when payment is set to 0,gets featured for free...
            if(core::config('payment.pay_to_go_on_feature') <= 0)
            {
                $ad->featured = Date::unix2mysql(time() + (core::config('payment.featured_days') * 24 * 60 * 60));
                try {
                    $ad->save();
                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }

                $this->redirect(Route::url('list')); 
            }

            $amount     = core::config('payment.pay_to_go_on_feature');
            $currency   = core::config('payment.paypal_currency');

            $order = Model_Order::new_order($ad, $ad->user, $id_product, $amount, $currency);

            // redirect to payment
            $this->redirect(Route::url('default', array('controller' =>'ad','action'=>'checkout' ,'id' => $order->id_order)));
        }
        else
            throw HTTP_Exception::factory(404,__('Page not found'));
    }
	
    /**
     * [action_buy] Pay for ad, and set new order 
     *
     */
    public function action_buy()
    {
        //check pay to featured top is enabled
        if(core::config('payment.paypal_seller') == FALSE)
            throw HTTP_Exception::factory(404,__('Page not found'));

        //getting the user that wants to buy now
        if (!Auth::instance()->logged_in())
        {
            Alert::set(Alert::INFO, __('To buy this product you first need to register'));
            $this->redirect(Route::url('oc-panel'));
        }

        $payer_user = Auth::instance()->get_user();
        
        $id_product = Model_Order::PRODUCT_AD_SELL;

        //check ad exists
        $id_ad  = $this->request->param('id');
        $ad     = new Model_Ad($id_ad);
            
        if($ad->loaded())
        {
            //do not allow selling if it already 0
            if(core::config('payment.stock')==0 OR ($ad->stock > 0 AND core::config('payment.stock')==1))
            {
                $amount     = $ad->price;
                $currency   = core::config('payment.paypal_currency');

                $order = Model_Order::new_order($ad, $payer_user, $id_product, $amount, $currency, __('Purchase').': '.$ad->seotitle);

                $this->redirect(Route::url('default', array('controller' =>'ad','action'=>'checkout' ,'id' => $order_id)));
            }
        }
        else
            throw HTTP_Exception::factory(404,__('Page not found'));
        
    }


    /**
     * pay an invoice, renders the paymenthods button, anyone with an ID of an order can pay it, we do not have control
     * @return [type] [description]
     */
    public function action_checkout()
    {
        $order = new Model_Order($this->request->param('id'));

        if ($order->loaded())
        {
            //if paid...no way jose
            if ($order->status != Model_Order::STATUS_CREATED)
            {
                Alert::set(Alert::INFO, __('This order was already paid.'));
                $this->redirect(Route::url('default'));
            }

            //template header
            $this->template->title              = __('Checkout').' '.Model_Order::product_desc($order->id_product);
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title ));

            $this->template->bind('content', $content);

            $this->template->content = View::factory('pages/ad/checkout',array('order' => $order)); 
        }
        else
        {
            //throw 404
            throw HTTP_Exception::factory(404,__('Page not found'));
        }
    }


	public function action_confirm_post()
	{
		$advert_id = $this->request->param('id');

		$advert = new Model_Ad($advert_id);

		if($advert->loaded())
		{
			if(core::config('general.moderation') == Model_Ad::EMAIL_CONFIRMATION)
			{

				$advert->status = Model_Ad::STATUS_PUBLISHED; // status active
				$advert->published = Date::unix2mysql();

				try 
				{
					$advert->save();

					//subscription is on
					$data = array(	'title' 		=> $title 		= 	$advert->title,
									'cat'			=> $cat 		= 	$advert->category,
									'loc'			=> $loc 		= 	$advert->location,	
								 );

					Model_Subscribe::find_subscribers($data, floatval(str_replace(',', '.', $advert->price)), $advert->seotitle); // if subscription is on
					
					Alert::set(Alert::INFO, __('Your advertisement is successfully activated! Thank you!'));
					$this->redirect(Route::url('ad', array('category'=>$advert->id_category, 'seotitle'=>$advert->seotitle)));	
				} 
				catch (Exception $e) 
				{
					throw HTTP_Exception::factory(500,$e->getMessage());
				}
			}
			if(core::config('general.moderation') == Model_Ad::EMAIL_MODERATION)
			{

				$advert->status = Model_Ad::STATUS_NOPUBLISHED;

				try 
				{
					$advert->save();
					Alert::set(Alert::INFO, __('Advertisement is received, but first administrator needs to validate. Thank you for being patient!'));
					$this->redirect(Route::url('ad', array('category'=>$advert->id_category, 'seotitle'=>$advert->seotitle)));	
				} 
				catch (Exception $e) 
				{
					throw HTTP_Exception::factory(500,$e->getMessage());
				}
			}
		}
	}

	public function action_advanced_search()
	{
		//template header
		$this->template->title           	= __('Advanced Search');
		$this->template->meta_description	= __('Advanced Search');

		//breadcrumbs
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title ));

        $this->template->scripts['footer'] = array('js/search.js');
		
		$pagination = NULL;
        $ads   = NULL;
		$user = (Auth::instance()->get_user() == NULL) ? NULL : Auth::instance()->get_user();

		if($this->request->query()) // after query has detected
		{			
        	// variables 
        	$search_advert 	= core::get('title');
        	$search_loc 	= core::get('location');

        	// filter by each variable
        	$ads = new Model_Ad();
        	
        	//if ad have passed expiration time dont show 
	        if(core::config('advertisement.expire_date') > 0)
	        {
	            $ads->where(DB::expr('DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY)'), '>', Date::unix2mysql());
	        }

	        if(!empty($search_advert) OR (core::get('search')!==NULL AND strlen(core::get('search'))>=3))
	        {	
	        	// if user is using search from header
	        	if(core::get('search'))
	        		$search_advert = core::get('search');

	        	$ads
        			->where_open()
                	->where('title', 'like', '%'.$search_advert.'%')
                	->or_where('description', 'like', '%'.$search_advert.'%')
                	->where_close();
	        }

        	$cf_fields = array();
            foreach ($this->request->query() as $name => $field) 
            {
            	// get by prefix
				if (strpos($name,'cf_') !== false) 
				{
					$cf_fields[$name] = $field;
					//checkbox when selected return string 'on' as a value
					if($field == 'on')
						$cf_fields[$name] = 1;
					elseif(empty($field)){
						$cf_fields[$name] = NULL;
					}
				}
        	}

	        $category = NULL;
            //filter by category 
            if (core::get('category')!==NULL)
            {
                $category = new Model_Category();
                $category->where('seoname','=',core::get('category'))->limit(1)->find();
                if ($category->loaded())
                    $ads->where('id_category', 'IN', $category->get_siblings_ids());
            }

	        $location = NULL;
            //filter by location 
            if (core::get('location')!==NULL)
            {
                $location = new Model_location();
                $location->where('seoname','=',core::get('location'))->limit(1)->find();
                if ($location->loaded())
                    $ads->where('id_location', 'IN', $location->get_siblings_ids());
            }

            //filter by price
            if (is_numeric(core::get('price-min')) AND is_numeric(core::get('price-max')))
            {
                $ads->where('price', 'BETWEEN', array(core::get('price-min'),core::get('price-max')));
            }

	        foreach ($cf_fields as $key => $value) 
	        {	
	        	if(isset($value) AND $value != NULL)
	        	{
		        	if(is_numeric($value))
		        		$ads->where($key, '=', $value);
		        	elseif(is_string($value))
		        		$ads->where($key, 'like', '%'.$value.'%');
		        }
	        }

	        $ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED);

	        // count them for pagination
			$res_count = $ads->count_all();

			if($res_count>0)
			{
			
				// pagination module
                $pagination = Pagination::factory(array(
                        'view'              => 'pagination',
                        'total_items'       => $res_count,
                        'items_per_page'    => core::config('general.advertisements_per_page'),
                ))->route_params(array(
                        'controller'        => $this->request->controller(),
                        'action'            => $this->request->action(),
                        'category'          => ($category!==NULL)?$category->seoname:NULL,
                ));

		        Breadcrumbs::add(Breadcrumb::factory()->set_title(__("Page ").$pagination->offset));
				
				$ads = $ads->order_by('published','desc')
							   ->limit($pagination->items_per_page)
			        	       ->offset($pagination->offset)
			        	       ->find_all();
			}
				
		}

		$this->template->bind('content', $content);

		$this->template->content = View::factory('pages/ad/advanced_search', array('ads'		      => $ads, 
        																		   'categories'	      => Model_Category::get_as_array(),
        																		   'order_categories' => Model_Category::get_multidimensional(),
        																		   'locations'	      => Model_Location::get_as_array(), 
        																		   'order_locations'  => Model_Location::get_multidimensional(),
        																		   'pagination'	      => $pagination, 
        																		   'user'		      => $user,
        																		   'fields' 		  => Model_Field::get_all(),
        																		   ));
                
		
	}

	
}// End ad controller

