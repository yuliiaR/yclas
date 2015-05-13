<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Order extends Auth_Crud {

    /**
    * @var $_index_fields ORM fields shown in index
    */
    protected $_index_fields = array('id_order','id_user', 'paymethod','amount','status');
    
    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'order';

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('update');

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Orders'))->set_url(Route::url('oc-panel',array('controller'  => 'order'))));
    }


        /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __('Orders');

        $this->template->styles = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/oc-panel/crud/index.js','js/oc-panel/stats/dashboard.js');

        $orders = new Model_Order();
        $orders = $orders->where('status', '=', Model_Order::STATUS_PAID);

        //filter email
        if (core::request('email')!==NULL)
        {
            $user = new Model_User();
            $user->where('email','=',core::request('email'))->limit(1)->find();
            if ($user->loaded())
                $orders = $orders->where('id_user', '=', $user->id_user);
        }

        //filter date
        if (!empty(Core::request('from_date')) AND !empty(Core::request('to_date')))
        {
            //Getting the dates range
            $from_date = Core::request('from_date',strtotime('-1 month'));
            $to_date   = Core::request('to_date',time());

            $orders = $orders->where('pay_date','between',array($from_date,$to_date));
        }

        //filter status
        if (is_numeric(core::request('status')))
        {
            $orders = $orders->where('status', '=', core::request('status'));
        }        

        $items_per_page = core::request('items_per_page',10);

        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $orders->count_all(),
                    'items_per_page' => $items_per_page,
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $orders = $orders->order_by('id_order','desc')
        ->limit($items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $pagination = $pagination->render();

        
        $this->render('oc-panel/pages/order/index', array('orders' => $orders,
            'pagination'=>$pagination));
    }    


}
