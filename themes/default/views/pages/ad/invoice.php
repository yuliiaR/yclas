<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="well recomentadion clearfix">
    <?//print_r($order)?>

    <a class="" href="<?=Route::url('default', array('controller'=> 'payment_paypal','action'=>'form' , 'id' => $order->id_order))?>">Paypal</a>
</div>

