<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Appearance Configuration')?></h1>
    <p><?=__('You can change the look and feel of your web site here.')?> <code><?=Theme::$theme?></code></p>
</div>


<div class="row-fluid">
<ul class="thumbnails">
<?$i=0;
foreach ($themes as $theme=>$info):?>
    <?if ($i%3==0):?></ul></div><div class="row-fluid"><ul class="thumbnails"><?endif?>
    <li class="span4">
    <div class="thumbnail">

        <?if ($scr = Theme::get_theme_screenshot($theme))?>
            <img src="<?=$scr?>">

        <div class="caption">
            <h3><?=$info['Name']?></h3>
            <p><?=$info['Description']?></p>
            <p><?=$info['License']?></p>
            <p>v<?=$info['Version']?></p>
            <p>
                <?if(Theme::$theme==$theme):?>
                    <span class="label label-info"><?=__('Active')?></span>
                <?else:?>
                <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'settings','action'=>'appearance','id'=>$theme))?>"><?=__('Activate')?></a>
                <a class="btn" target="_blank" href="http://open-classifieds.com/demo/?theme=<?=$theme?>"><?=__('Demo')?></a>
                <?endif?>
            </p>
        </div>
    </div>
    </li>
<?$i++;
endforeach?>
</ul>
</div><!--/row-->



Market Themes @todo
<div class="row-fluid">
<ul class="thumbnails">
              
<?for ($i=0; $i < 13; $i++):?>
    <?if ($i%3==0):?></ul></div><div class="row-fluid"><ul class="thumbnails"><?endif?>
    
    <li class="span4">
    <div class="thumbnail">
        <img src="http://lorempixel.com/300/200/?text=<?=rand()?>" alt="">
          <div class="caption">
      <h3>Theme <?=$i?></h3>
      <p><?=($i==0)?'<span class="label label-info">'.__('Active').'</span>':''?>
        Some description herea justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. 
    </p>
      <p>
        <a class="btn btn-primary" href=""><?=__('Buy Now')?></a>
        <a class="btn" target="_blank" href="http://open-classifieds.com/demo/?theme=<?=$i?>"><?=__('Demo')?></a>
        </p>
</div>
    </div>
</li>
<?endfor?>
</ul>
</div><!--/row-->