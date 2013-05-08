<?php defined('SYSPATH') or die('No direct script access.');?>

<? if (count($market)>1):?>
<div class="row-fluid">
<ul class="thumbnails">
<?$i=0;
foreach ($market as $item):?>
    <?if ($i%3==0):?></ul></div><div class="row-fluid"><ul class="thumbnails"><?endif?>
    <li class="span4">
    <div class="thumbnail <?if ( $item['price_offer']>0):?>alert-success<?endif?>" >

        <?if (empty($item['url_screenshot'])===FALSE):?>
            <img width="300px" height="200px" src="<?=$item['url_screenshot']?>">
        <?else:?>
             <img src="http://www.placehold.it/300x200&text=<?=$item['title']?>">
        <?endif?>   
        
        <div class="caption">
            <h3><?=$item['title']?></h3>
            <p>
                <?if ( $item['price_offer']>0):?>
                    <span class="badge badge-important">$<?=$item['price_offer']?></span>
                    <span class="badge badge-info"><del>$<?=$item['price']?></del></span>
                <?else:?>
                    <span class="badge badge-info">$<?=$item['price']?></span>
                <?endif?>
                <span class="badge badge-success"><?=$item['type']?></span>
            </p>
            <p>
                <?=$item['description']?>
            </p>
            <?if ( $item['price_offer']>0):?>
            <p>
                <a href="<?=$item['url_buy']?>" class="btn btn-block btn-danger"><?=__('Limited Offer!')?> $<?=$item['price_offer']?></a>
                <a href="<?=$item['url_buy']?>" class="btn btn-block btn-info"><i class="icon-time icon-white"></i> <?=__('Valid Until')?>  <?= Date::format($item['offer_valid'], core::config('general.date_format'))?></a>
            </p>
            <?endif?>
            <p>
                <a class="btn btn-primary btn-large" href="<?=$item['url_buy']?>">
                    <i class="icon-hdd icon-white"></i>  <?=__('Buy Now')?>
                </a>
                <?if (empty($item['url_demo'])===FALSE):?>
                    <a class="btn" target="_blank" href="<?=$item['url_demo']?>">
                        <i class="icon-eye-open"></i>
                        <? if ($item['type']!='theme'):?>
                            <?=__('More info')?>
                        <?else:?>
                            <?=__('Preview')?>
                        <?endif?>
                    </a>    
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