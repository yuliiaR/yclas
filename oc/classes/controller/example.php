<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Example extends Controller {

	public function action_index()
	{
	    
		
	}
	
	public function config()
	{
	    $config = new Config;
	    $config->attach(new Config);
	    $appearance = $config->load('appearance');
	    die(var_dump($appearance->theme));
	    
	    //die(Kohana::$config->load('appearance')->theme);
	}
	
	public function db_example()
	{
	    
	    
	     $query = DB::query(Database::SELECT,
	    'SELECT * FROM oc_posts
	    where status=1
	    limit 10');
	    $res = $query->cached(3)->execute()->as_array();
	    
	    
	     $query = DB::select('title','description')
	    ->from('posts')
	    ->where('status', '=', 1)
	    ->cached()
	    ->limit(10);
	    $res = $query->execute()->as_array();
	    
	    
	    //$posts = ORM::factory('post');
	    $posts = new Model_Ad();
	    $posts  ->where('status', '=', 1)
	    ->cached(5)
	    ->limit(10);
	    $res = print_r($posts->find(),1);
	}

} // example