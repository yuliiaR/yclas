<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form configuration, for optional fields 
 */

return array(

	'general'=>array(
		'created'=>FALSE,
		'description'=>TRUE,
		'parent_deep'=>TRUE,
	),
	'category'=>array(
		'seotitle'=>FALSE,
		'id_category_parent'=>TRUE,
		'price'=>TRUE,
	),
	'location'=>array(
		'seoname'=>TRUE,
		'lat'=>TRUE,
		'lng'=>TRUE,
	),
	'user'=>array(
		'id_location'=>TRUE,
		'last_modified'=>TRUE,
		'logins'=>TRUE,
		'last_login'=>TRUE,
		'last_ip'=>TRUE,
		'user_agent'=>TRUE,
		'token'=>TRUE,
		'token_created'=>TRUE,
		'token_expires'=>TRUE,
	),
	'role'=>array(
		'date_created'=>TRUE,
	),
	'content'=>array(
		'from_email'=>TRUE,
	),
);
