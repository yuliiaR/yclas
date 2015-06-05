<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Ads extends Api_User {

    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        try
        {
            if (is_numeric($this->request->param('id')))
            {
                $this->action_get();
            }
            else
            {
                $output = array();

                $ads = new Model_Ad();

                //any status but needs to see your ads ;)
                $ads->where('id_user','=',$this->user->id_user);

                //filter results by param, verify field exists and has a value and sort the results
                $ads->api_filter($this->_filter_params)->api_sort($this->_sort);

                //how many? used in header X-Total-Count
                $count = $ads->count_all();

                //pagination with headers
                $pagination = $ads->api_pagination( $this->_params, $count,
                                                    array(
                                                                'controller' => $this->request->controller(),
                                                                'action'     => $this->request->action(),
                                                                'version'    => 'v1',
                                                    ));

                $ads = $ads->cached()->find_all();

                //as array
                foreach ($ads as $ad)
                {
                    $output[$ad->id_ad] = $ad->as_array();
                    $output[$ad->id_ad]['thumb'] = ($ad->get_first_image()!==NULL)?Core::S3_domain().$ad->get_first_image():FALSE;
                    $output[$ad->id_ad]['customfields'] = Model_Field::get_by_category($ad->id_category);
                }

                $this->rest_output($output,200,$count,($pagination!==FALSE)?$pagination:NULL);
            }
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
    }

    public function action_get()
    {
        try
        {
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                $ad = new Model_Ad($id_ad);
                if ($ad->loaded())
                {
                    if ($ad->id_user == $this->user->id_user)
                    {
                        $a = $ad->as_array();
                        $a['images'] = $ad->get_images();
                        $a['category'] = $ad->category->as_array();
                        $a['location'] = $ad->location->as_array();
                        $a['customfields'] = Model_Field::get_by_category($ad->id_category);
                        $this->rest_output($a);
                    }
                    else
                        $this->_error(__('Not your advertisement'),401);
                }
                else
                    $this->_error(__('Advertisement not found'),404);
            }
            else
                $this->_error(__('Advertisement not found'),404);
            
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
       
    }

    /**
     * Handle POST requests.
     */
    public function action_create()
    {
        try
        {
            //TODO
            $this->rest_output($this->_params);
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
       
    }


    /**
     * Handle PUT requests.
     */
    public function action_update()
    {
        try
        {
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                $ad = new Model_Ad($id_ad);
                if ($ad->loaded())
                {
                    if ($ad->id_user == $this->user->id_user)
                    {
                        //TODO WORK with images, how we do that? new controllers? maybe /ads/image/5 delete you pass which image, if post we upload?
                        
                        //TODO how we set the status? check controller profile update to avoif duplicated code.
                        
                        //set values of the ad
                        $ad->values($this->_post_params);

                        try
                        {
                            $ad->last_modified = Date::unix2mysql();
                            $ad->save();
                            $this->rest_output('Ad updated');
                        }
                        catch (ORM_Validation_Exception $e)
                        {
                            $errors = '';
                            $e = $e->errors('ad');

                            foreach ($e as $f => $err) 
                                $errors.=$err.' - ';

                            $this->_error($errors,400);
                        }
                    }
                    else
                        $this->_error(__('Not your advertisement'),401);
                }
                else
                    $this->_error(__('Advertisement not found'),404);
            }
            else
                $this->_error(__('Advertisement not found'),404);

        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
       
    }


} // END
