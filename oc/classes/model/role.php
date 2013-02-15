<?php
/**
 * User Roles
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2009-2012 Open Classifieds Team
 * @license     GPL v3
 */
class Model_Role extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'roles';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_role';

    protected $_has_many = array(
        'access' => array(
            'model'   => 'access',
            'foreign_key' => 'id_role',
        ),
    );

     public function form_setup($form)
    {
        // get values from form form config file 
        $config = new Formconfig($this->request, $this->response);
        $conf =  $config->form();

        if ($conf['general']['description']) 
            $form->fields['description']['display_as'] = 'textarea';
    }

    public function exclude_fields()
    {
        // get values from form form config file 
        $config = new Formconfig($this->request, $this->response);
        $config = $config->form();
        
        $res = array();
        foreach ($config as $g => $value) 
        { 
            if($g == 'general' || $g == 'role')
            {
                foreach ($value as $value => $val) 
                {
                    if ($val == FALSE)
                    {
                        array_push($res, $value);   
                    }   
                }
            } 
                
        }
        
        return $res;
    }

}