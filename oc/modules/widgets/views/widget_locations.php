<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->locations_title?></h3>
<ul>
<?foreach($widget->loc_items as $loc):?>
    <li><a href="<?=Route::url('list',array('location'=>$loc->seoname,'category'=>$widget->cat_seoname))?>" title="<?=$loc->name?>">
        <?=$loc->name?></a>
    </li>
<?endforeach?>
</ul>