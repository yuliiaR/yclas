<?php defined('SYSPATH') or die('No direct script access.');?>

<h1>PAYPAL</h1>

		<?php 
		$sendbox = TRUE;
		if ($sendbox) $paypalWeb = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // TEST SANDBOX
		else $paypalWeb = 'https://www.paypal.com/cgi-bin/webscr';
		?>
	<div style="font-family: Arial; font-size: 20px; text-align: center; margin-top: 200px;">
		<?php _e('Please wait while we transfer you to Paypal');?><br />
		<img src="<?php //echo SITE_URL; ?>/images/loader.gif" border="0">
	</div>
		<form name="form1" id="form1" action="<?php echo $paypalWeb;?>" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="cbt" value="Return To <?=SITE_NAME ?>">
			<input type="hidden" name="business" value="<?php echo 'slobod_1360747823_biz@gmail.com';?>">
			<input type="hidden" name="item_name" value="<?= __('Pay to post in ').SITE_NAME ?>">
			<input type="hidden" name="item_number" value="<?php echo $idItem; ?>">
			<input type="hidden" name="amount" value="<?php echo $amount; ?>">
			<input type="hidden" name="quantity" value="1">
			<input type="hidden" name="undefined_quantity" value="0">
			<input type="hidden" name="no_shipping" value="0">
			<input type="hidden" name="shipping" value="0">
			<input type="hidden" name="shipping2" value="0">
			<input type="hidden" name="handling" value="0.00">
			<input type="hidden" name="return" value="<?=SITE_URL ?>">
			<input type="hidden" name="notify_url" value="<?=SITE_URL ?>/ipn.php">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="custom" value="">
			<input type="hidden" name="currency_code" value="<?=PAYPAL_CURRENCY ?>">
			<input type="hidden" name="rm" value="2">
			<input type="submit" value="Paypal">
		</form>
		<script type="text/javascript">form1.submit();</script>
<?php 
	die();
?>