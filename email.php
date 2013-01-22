<?php defined('SYSPATH') or die('No direct script access.');

// print_r($table = DB::select('ad.id_ad', 'category.title')->from('ad')->join('category')->on('ad.id_category', '=', 'category.id_category'));

$query = DB::select('ad.id_ad')->find_all();
print_r($query);