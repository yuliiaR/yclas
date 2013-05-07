<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Translations
 */


class Controller_Panel_Translations extends Auth_Controller {


    public function action_index()
    {

        // validation active 
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Translations')));  
        $this->template->title = __('Translations');     

        $this->template->bind('content', $content);

        $content = View::factory('oc-panel/pages/translations');

        $base_translation = DOCROOT.'languages/en_EN/LC_MESSAGES/messages.po';

        $path = DOCROOT.'languages/es_ES/LC_MESSAGES/messages.po';
        $default = $path;
        $path = DOCROOT.'languages/es_ES/LC_MESSAGES/messages.po';
        $default_mo = $path;

        //scan project files and generate .po
        $parse = $this->request->query('parse');
        if($parse)
        {
            //scan script
            require_once Kohana::find_file('vendor', 'POTCreator/POTCreator','php');

            $obj = new POTCreator;

            $obj->set_root(DOCROOT);
            $obj->set_exts('php|tpl');
            $obj->set_regular('/_[_|e]\([\"|\']([^\"|\']+)[\"|\']\)/i');
            $obj->set_base_path('..');
            $obj->set_read_subdir(true);

            $potfile = $base_translation;
            $obj->write_pot($potfile);
            Alert::set(Alert::SUCCESS, 'File regenerated');
            $this->request->redirect($this->request->url());
        }

        //pear gettext scripts
        require_once Kohana::find_file('vendor', 'GT/Gettext','php');
        require_once Kohana::find_file('vendor', 'GT/Gettext/PO','php');
        require_once Kohana::find_file('vendor', 'GT/Gettext/MO','php');
        //.po to .mo script
        require_once Kohana::find_file('vendor', 'php-mo/php-mo','php');

        //load the .po files
        $pocreator_en = new File_Gettext_PO();
        $pocreator_en->load($base_translation);
        $pocreator_default = new File_Gettext_PO();
        $pocreator_default->load($default);


        if($this->request->post())
        {
            $keys = $this->request->post('keys');
            $translations = $this->request->post('translations');

            $strings = array();
            $out = '';
            foreach($translations as $key => $value)
            {
                if($value != "")
                {
                    $strings[$keys[$key]] = $value;
                    $out .= '#: String '.$key.PHP_EOL;
                    $out .= 'msgid "'.$keys[$key].'"'.PHP_EOL;
                    $out .= 'msgstr "'.$value.'"'.PHP_EOL;
                    $out .= PHP_EOL;
                }
            }
            //write the generated .po to file
            $fp = fopen($default, 'w+');
            $read = fwrite($fp, $out);
            fclose($fp);

            $pocreator_default->strings = $strings;

            //generate the .mo from the .po file
            phpmo_convert($default);
        }

        $content->strings_en = $pocreator_en->strings;
        $content->strings_default = $pocreator_default->strings;

    }



}//end of controller