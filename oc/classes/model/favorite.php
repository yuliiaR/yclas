<?php defined('SYSPATH') or die('No direct script access.');
/**
 * user favorite ads
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2014 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Favorite extends ORM {


    /**
     * @var  string  Table name
     */
    protected $_table_name = 'favorites';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_favorite';

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'ad' => array(
                'model'       => 'ad',
                'foreign_key' => 'id_ad',
            ),
        'user' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ),
    );

}