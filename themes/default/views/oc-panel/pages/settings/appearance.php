<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Appearance Configuration')?></h1>
    <p><?=__('You can change the look and feel of your web site here.')?> <code><?=Theme::$theme?></code></p>
</div>

<div class="row-fluid">
<?for ($i=0; $i < 13; $i++):?>
    <?if ($i%3==0):?></div><div class="row-fluid"><?endif?>
    <div class="span4 well">
      <h2>Theme <?=$i?></h2>
      <p><?=($i==0)?'<span class="label label-info">'.__('Active').'</span>':''?>
        Some description herea justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. 
    </p>
      <p>
        <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'settings','action'=>'appearance','id'=>$i))?>"><?=__('Activate')?></a>
        <a class="btn" href="http://open-classifieds.com/demo/?theme=<?=$i?>"><?=__('Demo')?></a>
        </p>
    </div><!--/span-->
<?endfor?>
</div><!--/row-->