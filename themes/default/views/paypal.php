<?php defined('SYSPATH') or die('No direct script access.');?>

<h1>PAYPAL</h1>

		
	<div style="font-family: Arial; font-size: 20px; text-align: center; margin-top: 200px;">
		<?php _e('Please wait while we transfer you to Paypal');?><br />
		<img src="<?//php echo 'reoc.zz.mu/paypal'; ?>/images/loader.gif" border="0">
	</div>
		<form name="form1" id="form1" action="<?=$paypalWeb;?>" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="cbt" value="Return To <?=$site_name?>">
			<input type="hidden" name="business" value="<?=$paypal_account?>">
			<input type="hidden" name="item_name" value="<?=$paypal_msg.' '.$site_name ?>">
			<input type="hidden" name="item_number" value="<?=$order_id ?>">
			<input type="hidden" name="amount" value="<?=$amount ?>">
			<input type="hidden" name="quantity" value="1">
			<input type="hidden" name="undefined_quantity" value="0">
			<input type="hidden" name="no_shipping" value="0">
			<input type="hidden" name="shipping" value="0">
			<input type="hidden" name="shipping2" value="0">
			<input type="hidden" name="handling" value="0.00">
			<input type="hidden" name="return" value="<?=$site_url?>">
			<input type="hidden" name="notify_url" value="<?=$site_url?>/ipnlistener.html">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="custom" value="">
			<input type="hidden" name="currency_code" value="<?=$paypal_currency?>">
			<input type="hidden" name="rm" value="2">
			<input type="submit" value="Paypal">
		</form>
		<script type="text/javascript">form1.submit();</script>