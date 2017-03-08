<?php defined('SYSPATH') or die('No direct script access.');?>
<form name="payfast" id="payfast" method="post" action="<?=$form_action?>">
<input type="hidden" Name="merchant_id" value="<?=Core::config('payment.payfast_merchant_id')?>"/>
<input type="hidden" Name="merchant_key" value="<?=Core::config('payment.payfast_merchant_key')?>"/>
<input type="hidden" Name="return_url" value="<?=URL::base(TRUE)?>"/>
<input type="hidden" Name="cancel_url" value="<?=Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order))?>"/>
<input type="hidden" Name="notify_url" value="<?=Route::url('default',array('controller'=>'payfast', 'action'=>'itn','id'=>'1'))?>"/>
<input type="hidden" Name="m_payment_id" value="<?=$order->id_order?>"/>
<input type="hidden" Name="amount" value="<?=$order->amount?>"/>
<input type="hidden" Name="item_name" value="<?=$order->description?>"/>
<input type="hidden" Name="item_description" value="<?=$order->description?>"/>
<button class="btn btn-info pay-btn full-w" type="submit"><span class="glyphicon glyphicon-shopping-cart"></span> <?=_e('Pay Now')?></button>
</form >