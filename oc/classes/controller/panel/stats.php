<?php defined('SYSPATH') or die('No direct script access.');
/**
 * OC statistics!
 *
 * @package    OC
 * @category   Stats
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Stats extends Auth_Controller {

   


    public function action_index()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats')));

        $this->template->styles = array('css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                    'js/oc-panel/stats/dashboard.js');
        
        $this->template->title = __('Stats');
        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/pages/stats/dashboard');

        //Getting the dates and range
        $from_date = Core::post('from_date',strtotime('-1 month'));
        $to_date   = Core::post('to_date',time());

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        //mysql formated dates
        $my_from_date = Date::unix2mysql($from_date);
        $my_to_date   = Date::unix2mysql($to_date);

        //dates range we are filtering
        $dates     = Date::range($from_date, $to_date,'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');
        
        //dates displayed in the form
        $content->from_date = date('Y-m-d',$from_date);
        $content->to_date   = date('Y-m-d',$to_date) ;


        //ads published last XX days
        $query = DB::select(DB::expr('DATE(published) date'))
                        ->select(DB::expr('COUNT(id_ad) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        //->where(DB::expr('TIMESTAMPDIFF( DAY , published, NOW() )') ,'<=','30')
                        ->where('published','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( published )'))
                        ->order_by('date','asc')
                        ->execute();

        $ads = $query->as_array('date');

        $ads_daily = array();
        foreach ($dates as $date) 
        {
            $count = (isset($ads[$date['date']]['count']))?$ads[$date['date']]['count']:0;
            $ads_daily[] = array('date'=>$date['date'],'count'=> $count);
        } 

        $content->ads_daily =  $ads_daily;


        //Today and Yesterday Ads
        $query = DB::select(DB::expr('COUNT(id_ad) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->where('published','between',array(date('Y-m-d',strtotime('-1 day')),date::unix2mysql()))
                        ->group_by(DB::expr('DATE( published )'))
                        ->order_by('published','asc')
                        ->execute();

        $ads = $query->as_array();
        $content->ads_yesterday = (isset($ads[0]['count']))?$ads[0]['count']:0;
        $content->ads_today     = (isset($ads[1]['count']))?$ads[1]['count']:0;


        //Last 30 days ads
        $query = DB::select(DB::expr('COUNT(id_ad) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->where('published','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();

        $ads = $query->as_array();
        $content->ads_month = (isset($ads[0]['count']))?$ads[0]['count']:0;

        //total ads
        $query = DB::select(DB::expr('COUNT(id_ad) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->execute();

        $ads = $query->as_array();
        $content->ads_total = (isset($ads[0]['count']))?$ads[0]['count']:0;

        /////////////////////VISITS STATS////////////////////////////////

        //visits created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $visits = $query->as_array('date');

        $visits_daily = array();
        foreach ($dates as $date) 
        {
            $count = (isset($visits[$date['date']]['count']))?$visits[$date['date']]['count']:0;
            $visits_daily[] = array('date'=>$date['date'],'count'=> $count);
        } 

        $content->visits_daily =  $visits_daily;


        //Today and Yesterday Views
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('created','between',array(date('Y-m-d',strtotime('-1 day')),date::unix2mysql()))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_yesterday = (isset($visits[0]['count']))?$visits[0]['count']:0;
        $content->visits_today     = (isset($visits[1]['count']))?$visits[1]['count']:0;


        //Last 30 days visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();

        $visits = $query->as_array();
        $content->visits_month = (isset($visits[0]['count']))?$visits[0]['count']:0;

        //total visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_total = (isset($visits[0]['count']))?$visits[0]['count']:0;
        
    }




}