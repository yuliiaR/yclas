<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form configuration, for optional fields 
 */
class Formconfig {
	
	public function form(){

		return array(

		'general'=>array(
			'created'=>FALSE,
			'description'=>FALSE,
			'parent_deep'=>FALSE,
		),
		'category'=>array(
			'seotitle'=>TRUE,
			'id_category_parent'=>TRUE,
			'price'=>TRUE,
		),
		'location'=>array(
			'seoname'=>TRUE,
			'lat'=>FALSE,
			'lng'=>FALSE,
		),
		'user'=>array(
			'id_location'=>FALSE,
			'last_modified'=>FALSE,
			'logins'=>FALSE,
			'last_login'=>FALSE,
			'last_ip'=>FALSE,
			'user_agent'=>FALSE,
			'token'=>FALSE,
			'token_created'=>FALSE,
			'token_expires'=>FALSE,
		),
		'role'=>array(
			'date_created'=>FALSE,
		),
		'content'=>array(
			'from_email'=>TRUE,
		),
		'captcha'=>array(
			'captcha'=>TRUE,
		),
		);	
	}
	
}