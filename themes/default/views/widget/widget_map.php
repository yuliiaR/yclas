<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->map_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->map_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <iframe frameborder="0" noresize="noresize" 
            height="<?=$widget->map_height+($widget->map_height*0.10)?>px" width="100%" 
            src="<?=Route::url('map')?>?height=<?=$widget->map_height?>&controls=0&zoom=<?=$widget->map_zoom?>">
    </iframe>
</div>