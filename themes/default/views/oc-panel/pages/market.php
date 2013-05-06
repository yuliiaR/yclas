<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Market Extras')?></h1>
    <p><?=__('You can change the look and feel of your web site here.')?> <code><?=Theme::$theme?></code></p>
</div>




Market Themes @todo from json?
<div class="row-fluid">
<ul class="thumbnails">
              
<?for ($i=0; $i < 11; $i++):?>
    <?if ($i%3==0):?></ul></div><div class="row-fluid"><ul class="thumbnails"><?endif?>
    
    <li class="span4">
    <div class="thumbnail">
        <img src="http://lorempixel.com/300/200/?text=<?=rand()?>" alt="">
          <div class="caption">
      <h3>Theme <?=$i?></h3>
      <p><span class="badge badge-info">$<?=rand(1,90)?></span>
        Some description herea justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. 
    </p>

      <p>
        <a class="btn btn-primary" href=""><?=__('Buy Now')?></a>
        <a class="btn" target="_blank" href="http://open-classifieds.com/demo/?theme=<?=$i?>"><?=__('Read More')?></a>
        </p>
</div>
    </div>
</li>
<?endfor?>
</ul>
</div><!--/row-->