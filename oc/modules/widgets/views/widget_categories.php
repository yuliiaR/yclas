<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->categories_title?></h3>
<ul>
<?foreach($widget->cat_items as $cat):?>
    <li><a href="<?=Route::url('list',array('category'=>$cat->seoname))?>" title="<?=$cat->name?>">
        <?=$cat->name?></a>
    </li>
<?endforeach?>
</ul>