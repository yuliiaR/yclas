<?php defined('SYSPATH') or die('No direct script access.');?>

<? if (count($market)>1):?>
<div class="row-fluid">
<ul class="thumbnails">
<?$i=0;
foreach ($market as $item):?>
    <?if ($i%3==0):?></ul></div><div class="row-fluid"><ul class="thumbnails"><?endif?>
    <li class="span4">
    <div class="thumbnail">

        <?if (empty($item['url_screenshot'])===FALSE):?>
            <img width="300px" height="200px" src="<?=$item['url_screenshot']?>">
        <?else:?>
             <img src="http://www.placehold.it/300x200&text=<?=$item['title']?>">
        <?endif?>   
        
        <div class="caption">
            <h3><?=$item['title']?></h3>
            <p>
                <span class="badge badge-info">$<?=number_format($item['price'],2)?></span>
                <span class="badge badge-success"><?=$item['type']?></span>
            </p>
            <p>
                <?=$item['description']?>
            </p>
            <p>
                <a class="btn btn-primary" href="<?=$item['url_buy']?>"><?=__('Buy Now')?></a>
                <?if (empty($item['url_demo'])===FALSE):?>
                    <a class="btn" target="_blank" href="<?=$item['url_demo']?>"><?=__('More info')?></a>    
                <?endif?>
            </p>
        </div>
    </div>
    </li>
    <?$i++;
    endforeach?>
</ul>
</div><!--/row-->
<?endif?>