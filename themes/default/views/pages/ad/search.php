<?php defined('SYSPATH') or die('No direct script access.');?>

<p>Seach Page</p>


<?= FORM::open(Route::url('search'), array('class'=>'navbar-search pull-left', 'method'=>'GET', 'action'=>'','enctype'=>'multipart/form-data'))?>
    <input type="text" name="advert" class="search-query span2" placeholder="<?=__('Search')?>">
    <input type="text" name="cat" class="search-query span2" placeholder="<?=__('Search')?>">
    <input type="text" name="loc" class="search-query span2" placeholder="<?=__('Search')?>">
	<?= FORM::button('submit', 'Search', array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('search')))?> 
<?= FORM::close()?>

