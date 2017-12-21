<?php defined('SYSPATH') or die('No direct script access.');?>
<form method="post" action="<?=$url?>">
<input type="hidden" name="names" value="<?=$order->user->name?>">
<input type="hidden" name="amount" value="<?=$order->amount?>">
<input type="hidden" name="email_address" value="<?=$order->user->email?>">
<input type="hidden" name="phone_number" value="<?=$order->user->phone?>">
<input type="hidden" name="currency" value="NGN">
<input type="hidden" name="merch_txnref" value="<?=$order->id_order?>">
<input type="hidden" name="merchantid" value="<?=$merchantid?>">
<input type="hidden" name="redirect_url" value="<?=Route::url('default', ['controller' => 'zenith', 'action' => 'result', 'id' => 1])?>">
<input name="cmdsubmit" class="btn btn-success" type="submit" value="<?=__('Pay with Card')?>" >
</form>