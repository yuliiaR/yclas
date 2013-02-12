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
        $config = Kohana::$config->load('form');
        $general = $config->get('general');

        if ($general['description']) 
            $form->fields['description']['display_as'] = 'textarea';
    }

    public function exclude_fields()
    {
        // get values from form form config file 
        $config = Kohana::$config->load('form');
        $general = $config->get('general');
        $role = $config->get('role'); 
        
        $res = array();
        foreach ($general as $g => $value) 
        {
            if($value == FALSE)
            {
                array_push($res, $g);
            }
        }
        foreach($role as $c => $value)
        {
            if($value == FALSE)
            {
                array_push($res, $c);
            }
        }
        return $res; 
    }

}