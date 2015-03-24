<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->text_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->text_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <?if (!is_null($widget->info)):?>
        <p><?=$widget->info->views?> <strong><?=__('views')?></strong></p>
        <p><?=$widget->info->ads?> <strong><?=__('ads')?></strong></p>
        <p><?=$widget->info->users?> <strong><?=__('users')?></strong></p>
    <?endif?>
</div>
