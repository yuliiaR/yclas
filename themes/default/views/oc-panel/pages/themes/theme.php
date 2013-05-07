<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Themes')?></h1>
    <p><?=__('You can change the look and feel of your web site here.')?></p>
</div>

<div class="media">
    <?if ($scr = Theme::get_theme_screenshot(Theme::$theme))?>
            <img class="media-object pull-left" width="150px" height="100px" src="<?=$scr?>">
    <div class="media-body">
        <h4 class="media-heading"><?=$selected['Name']?></h4>
        <p>
            <span class="badge badge-info"><?=__('Current Theme')?></span>
            <?if (Theme::has_options()):?>
            <a href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>">
                <i class="icon-leaf"></i> <?=__('Theme Options')?></a>
            <?endif?>
        </p>
        <p><?=$selected['Description']?></p>
    </div>
</div>

<? if (count($themes)>1):?>
<h2><?=__('Available Themes')?></h2>
<div class="row-fluid">
<ul class="thumbnails">
<?$i=0;
foreach ($themes as $theme=>$info):?>
    <?if(Theme::$theme!==$theme):?>
    <?if ($i%3==0):?></ul></div><div class="row-fluid"><ul class="thumbnails"><?endif?>
    <li class="span4">
    <div class="thumbnail">

        <?if ($scr = Theme::get_theme_screenshot($theme)):?>
            <img width="300px" height="200px" src="<?=$scr?>">
        <?endif?>

        <div class="caption">
            <h3><?=$info['Name']?></h3>
            <p><?=$info['Description']?></p>
            <p><?=$info['License']?> v<?=$info['Version']?></p>
            <p>
                <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'index','id'=>$theme))?>"><?=__('Activate')?></a>
                <a class="btn" target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>"><?=__('Preview')?></a>    
            </p>
        </div>
    </div>
    </li>
    <?$i++;
    endif?>
<?endforeach?>
</ul>
</div><!--/row-->
<?endif?>


<h2><?=__('Market Themes')?></h2>
<p><?=__('Here you can find a selection from our premium themes.')?></p>
<p class="text-success"><?=__('All themes include support, updates and license.')?></p>

<?=View::factory('oc-panel/pages/market/listing',array('market'=>$market))?>    