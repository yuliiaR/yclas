<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CONTROLLER NEW 
 */
class Controller_New extends Controller
{
	
    /**
     * 
     * NEW ADVERTISEMENT 
     * 
     */
    public function action_index()
    {
        //Detect early spam users, show him alert
        if (core::config('general.black_list') == TRUE AND Model_User::is_spam(Core::post('email')) === TRUE)
        {
            Alert::set(Alert::ALERT, __('Your profile has been disable for posting, due to recent spam content! If you think this is a mistake please contact us.'));
            $this->redirect('default');
        }

        //advertisement.only_admin_post
        if( Core::config('advertisement.only_admin_post')==1 AND (
            !Auth::instance()->logged_in() OR  
            (Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role != Model_Role::ROLE_ADMIN)))
        {
            $this->redirect('default');
        }
        
        if (Core::post('ajaxValidateCaptcha'))
        {
            $this->auto_render = FALSE;
            $this->template = View::factory('js');

            if (captcha::check('publish_new', TRUE))
                $this->template->content = 'true';
            else
                $this->template->content = 'false';
            
            return;
        }
        
        //template header
        $this->template->title              = __('Publish new advertisement');
        $this->template->meta_description   = __('Publish new advertisement');
        
        
        $this->template->styles = array('css/jquery.sceditor.default.min.css' => 'screen',
                                        'css/jasny-bootstrap.min.css' => 'screen',
                                        '//cdn.jsdelivr.net/sweetalert/0.1.2/sweet-alert.min.css' => 'screen');
                                        
        $this->template->scripts['footer'][] = 'js/jquery.sceditor.min.js';
        $this->template->scripts['footer'][] = 'js/jquery.sceditor.bbcode.min.js';
        $this->template->scripts['footer'][] = 'js/jasny-bootstrap.min.js';
        $this->template->scripts['footer'][] = 'js/jquery.chained.min.js';
        $this->template->scripts['footer'][] = '//cdn.jsdelivr.net/sweetalert/0.1.2/sweet-alert.min.js';
        $this->template->scripts['footer'][] = '//cdnjs.cloudflare.com/ajax/libs/ouibounce/0.0.10/ouibounce.min.js';
        if(core::config('advertisement.map_pub_new'))
        {
            $this->template->scripts['footer'][] = '//maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7';
            $this->template->scripts['footer'][] = '//cdn.jsdelivr.net/gmaps/0.4.15/gmaps.min.js';
        }
        $this->template->scripts['footer'][] = 'js/new.js?v='.Core::VERSION;

        // redirect to login, if conditions are met 
        if(core::config('advertisement.login_to_post') == TRUE AND !Auth::instance()->logged_in())
        {
            Alert::set(Alert::INFO, __('Please, login before posting advertisement!'));
            HTTP::redirect(Route::url('oc-panel',array('controller'=>'auth','action'=>'login')));
        }

        //find all, for populating form select fields 
        $categories         = Model_Category::get_as_array();  
        $order_categories   = Model_Category::get_multidimensional();
        $order_parent_deep  = Model_Category::get_by_deep();

        // NO categories redirect ADMIN to categories panel
        if(count($order_categories) == 0)
        {
            if(Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN)
            {
                Alert::set(Alert::INFO, __('Please, first create some categories.'));
                $this->redirect(Route::url('oc-panel',array('controller'=>'category','action'=>'index')));
            }
            else
            {
                Alert::set(Alert::INFO, __('Posting advertisements is not yet available.'));
                $this->redirect('default');
            }
        }

        //get locations
        $locations         = Model_Location::get_as_array();  
        $order_locations   = Model_Location::get_multidimensional();
        $loc_parent_deep   = Model_Location::get_by_deep();
        
        // bool values from DB, to show or hide this fields in view
        $form_show = array('captcha'    =>core::config('advertisement.captcha'),
                           'website'    =>core::config('advertisement.website'),
                           'phone'      =>core::config('advertisement.phone'),
                           'location'   =>core::config('advertisement.location'),
                           'address'    =>core::config('advertisement.address'),
                           'price'      =>core::config('advertisement.price'));
        
        
        $id_category = NULL;
        $selected_category = new Model_Category();
        //if theres a category by post or by get
        if (Core::request('category')!==NULL)
        {
            if (is_numeric(Core::request('category')))
                $selected_category->where('id_category','=',core::request('category'))->limit(1)->find();
            else
                $selected_category->where('seoname','=',core::request('category'))->limit(1)->find();

            if ($selected_category->loaded())
                $id_category = $selected_category->id_category;
        }

        $id_location = NULL;
        $selected_location = new Model_Location();
        //if theres a location by post or by get
        if (Core::request('location')!==NULL)
        {
            if (is_numeric(Core::request('location')))
                $selected_location->where('id_location','=',core::request('location'))->limit(1)->find();
            else
                $selected_location->where('seoname','=',core::request('location'))->limit(1)->find();

            if ($selected_location->loaded())
                $id_location = $selected_location->id_location;
        }

        //render view publish new
        $this->template->content = View::factory('pages/ad/new', array('categories'         => $categories,
                                                                       'order_categories'   => $order_categories,
                                                                       'order_parent_deep'  => $order_parent_deep,
                                                                       'locations'          => $locations,
                                                                       'order_locations'    => $order_locations,
                                                                       'loc_parent_deep'    => $loc_parent_deep,
                                                                       'form_show'          => $form_show,
                                                                       'id_category'        => $id_category,
                                                                       'selected_category'  => $selected_category,
                                                                       'id_location'        => $id_location,
                                                                       'selected_location'  => $selected_location,
                                                                       'fields'             => Model_Field::get_all()));
        if($this->request->post()) 
        {
            if(captcha::check('publish_new')) 
            {
                $data = $this->request->post();

                $validation = Validation::factory($data);

                //validate location since its optional
                if(core::config('advertisement.location'))
                {
                    if(count($locations) > 1)
                        $validation = $validation->rule('location', 'not_empty')
                        ->rule('location', 'digit');
                }

                //user is not logged in validate input
                if(!Auth::instance()->logged_in())
                {
                    $validation = $validation->rule('email', 'not_empty')
                    ->rule('email', 'email')

                    ->rule('name', 'not_empty')
                    ->rule('name', 'min_length', array(':value', 2))
                    ->rule('name', 'max_length', array(':value', 145));
                }


                if($validation->check())
                {       

                    // User detection, if doesnt exists create
                    if (!Auth::instance()->logged_in()) 
                        $user = Model_User::create_email(core::post('email'), core::post('name'));
                    else
                        $user = Auth::instance()->get_user();

                    //to make it backward compatible with older themes: UGLY!!
                    if (isset($data['category']) AND is_numeric($data['category']))
                    {
                        $data['id_category'] = $data['category'];
                        unset($data['category']);
                    }

                    if (isset($data['location']) AND is_numeric($data['location']))
                    {
                        $data['id_location'] = $data['location'];
                        unset($data['location']);
                    }
                    
                    //lets create!!
                    $return = Model_Ad::new_ad($data,$user);

            
                    //there was an error on the validation
                    if (isset($return['validation_errors']) AND is_array($return['validation_errors']))
                    {
                        foreach ($return['validation_errors'] as $f => $err) 
                            Alert::set(Alert::ALERT, $err);
                    }
                    //another error
                    elseif (isset($return['error']))
                    {
                        Alert::set($return['error_type'], $return['error']);
                    }
                    //success!!!
                    elseif (isset($return['message']) AND isset($return['ad']))
                    {
                        $new_ad = $return['ad'];

                        // IMAGE UPLOAD 
                        $filename = NULL;

                        for ($i=0; $i < core::config('advertisement.num_images'); $i++) 
                        { 
                            if (isset($_FILES['image'.$i]))
                                $filename = $new_ad->save_image($_FILES['image'.$i]);

                            if ($filename)
                                $new_ad->has_images++;
                        }
                        //since theres images save the ad again...
                        if ($new_ad->has_images > 0)
                        {
                            try 
                            {
                                $new_ad->save();
                            } 
                            catch (Exception $e) 
                            {
                                throw HTTP_Exception::factory(500,$e->getMessage());
                            }
                        }

                        Alert::set(Alert::SUCCESS, $return['message']);

                        //redirect user
                        if (isset($return['checkout_url']) AND !empty($return['checkout_url']))
                            $this->redirect($return['checkout_url']);
                        else
                            $this->redirect(Route::url('default', array('action'=>'thanks','controller'=>'ad','id'=>$ad->id_ad)));
                    }
                }
                else
                {
                    $errors = $validation->errors('ad');
                    foreach ($errors as $f => $err) 
                    {
                        Alert::set(Alert::ALERT, $err);
                    }
                }
            }
            else
            {
                Alert::set(Alert::ALERT, __('Captcha is not correct'));
            }

            
        }
        
    }


}
