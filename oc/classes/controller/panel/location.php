<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Location extends Auth_Crud {



	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_location','name','id_location_parent');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'location';



    /**
     * CRUD controller: DELETE
     */
    public function action_delete()
    {
        $this->auto_render = FALSE;

        $location = new Model_Location($this->request->param('id'));

        //update the elements related to that ad
        if ($location->loaded())
        {
            //update all the siblings this location has and set the location parent
            $query = DB::update('locations')
                        ->set(array('id_location_parent' => $location->id_location_parent))
                        ->where('id_location_parent','=',$location->id_location)
                        ->execute();

            //update all the ads this location has and set the location parent
            $query = DB::update('ads')
                        ->set(array('id_location' => $location->id_location_parent))
                        ->where('id_location','=',$location->id_location)
                        ->execute();

            try
            {
                $location->delete();
                $this->template->content = 'OK';
                Alert::set(Alert::SUCCESS, __('Success, location deleted'));
                
            }
            catch (Exception $e)
            {
                 Alert::set(Alert::ERROR, $e->getMessage());
            }
        }
        else
             Alert::set(Alert::SUCCESS, __('Location not deleted'));

        
        Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'location','action'=>'index')));  

    }
}
