<?php
/**
 * Controllers user access
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2009-2012 Open Classifieds Team
 * @license     GPL v3
 */
class Model_Access extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'access';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_access';


    public function form_setup($form)
    {
        $form->fields['password']['display_as'] = 'password';
    }

    public function exclude_fields()
    {
        return array('id_user', 'salt', 'date_created', 'date_lastlogin', 'ip_created', 'ip_lastlogin');
    }
}