<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date helper class
 *
* @package    OC
* @category   Date
* @author     Chema <chema@garridodiaz.com>
* @copyright  (c) 2009-2011 Open Classifieds Team
* @license    GPL v3
*/
class Date extends Kohana_Date {
    
    /**
     * Formats the given date into another format
     * 
     * @param string $date
     * @param string $format
     * @return string
     */
	public static function format($date, $format='d/m/Y')
	{
	   return date($format, strtotime($date));
	}
	
	/**
	 * 
	 * Converts a Unix time stamp to a MySQL date
	 * @param integer $date
	 * @return string
	 */
	public static function unix2mysql($date)
	{
	    return date(Date::$timestamp_format,$date);
	}
	
	/**
	 * 
	 * Converts a MySQL date to a Unix date
	 * @param unknown_type $date
	 * @return number
	 */
	public static function mysql2unix($date)
	{
	    return strtotime($date);
	}
	
   
    
} // End Date