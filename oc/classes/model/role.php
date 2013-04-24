<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User Roles
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
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
      
        $form->fields['description']['display_as'] = 'textarea';
    }

    public function exclude_fields()
    {
        return array('date_created');
    }

}