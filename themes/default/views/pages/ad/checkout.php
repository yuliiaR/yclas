<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="well recomentadion clearfix">
    <h1><?=__('Checkout')?></h1>

    <p>
    <h2><?=$order->ad->title?></h2>

    <?=Model_Order::product_desc($order->id_product)?>

    <?=$order->description?>

    <?=i18n::format_currency($order->amount, $order->currency)?>
    </p>
    
    <a class="btn btn-success" 
            href="<?=Route::url('default', array('controller'=> 'paypal','action'=>'pay' , 'id' => $order->id_order))?>">
            <?=__('Pay with Paypal')?></a>

    <?if ($order->id_product!==Model_Order::PRODUCT_AD_SELL):?>

    <?endif?>
    
</div>

