<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Plan extends Auth_CrudAjax {

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'plan';

    /**
     *
     * Contruct that checks you are loged in before nothing else happens!
     */
    function __construct(Request $request, Response $response)
    {
        if (Theme::get('premium')!=1)
        {
            Alert::set(Alert::INFO,  __('Upgrade your Open Classifieds site to activate this feature.'));
        }
       
        parent::__construct($request,$response);
    }
}