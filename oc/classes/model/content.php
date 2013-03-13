<?php defined('SYSPATH') or die('No direct script access.');
/**
 * content
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */
class Model_Content extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'content';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_content';


    /**
     * get the model filtered
     * @param  string $seotitle
     * @param  string $type
     */
    public static function get($seotitle, $type = 'page')
    {   
        $content = new self();
        $content = $content->where('seotitle','=', $seotitle)
                 ->where('type','=', $type)
                 ->where('status','=', 1)
                 ->limit(1)->cached()->find();

        //was not found try EN translation...
        if (!$content->loaded())
        {

            $content = $content->where('seotitle','=', $seotitle)
                 ->where('type','=', $type)
                 ->where('status','=', 1)
                 ->limit(1)->cached()->find();
        }

        return $content;
    }

    /**
     * get the model filtered
     * @param  string $seotitle
     * @param  array $replace try to find the matches and replace them
     * @param  string $type
     */
    public static function text($seotitle, $replace = NULL, $type = 'page')
    {
        if ($replace===NULL) $replace = array();
        $content = self::get($seotitle, $type);
        if ($content->loaded())
        {
            $user = Auth::instance()->get_user();

            //adding extra replaces
            $replace+= array('[USER.NAME]' =>  $user->name,
                             '[USER.EMAIL]' =>  $user->email
                            );

            return str_replace(array_keys($replace), array_values($replace), $content->description);
        }
        return FALSE;

    }

    public function form_setup($form)
    {
        $form->fields['order']['display_as']            = 'select';
        $form->fields['order']['options']               = range(0, 30);
    }

    public function exclude_fields()
    {
        return array('created');
    }

} // END Model_Content