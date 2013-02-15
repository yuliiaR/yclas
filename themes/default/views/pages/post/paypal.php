<?php defined('SYSPATH') or die('No direct script access.');?>

<h1>PAYPAL</h1>

		<?php 
		$sendbox = TRUE;
		if ($sendbox) $paypalWeb = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // TEST SANDBOX
		else $paypalWeb = 'https://www.paypal.com/cgi-bin/webscr';
		?>
	<div style="font-family: Arial; font-size: 20px; text-align: center; margin-top: 200px;">
		<?php _e('Please wait while we transfer you to Paypal');?><br />
		<img src="<?php echo 'reoc.zz.mu/paypal'; ?>/images/loader.gif" border="0">
	</div>
		<form name="form1" id="form1" action="<?php echo $paypalWeb;?>" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="cbt" value="Return To <?='reoc' ?>">
			<input type="hidden" name="business" value="<?php echo 'slobod_1360747823_biz@gmail.com';?>">
			<input type="hidden" name="item_name" value="<?= __('Pay to post in ').'reoc' ?>">
			<input type="hidden" name="item_number" value="<?php echo '7'; ?>">
			<input type="hidden" name="amount" value="<?php echo '30.99'; ?>">
			<input type="hidden" name="quantity" value="1">
			<input type="hidden" name="undefined_quantity" value="0">
			<input type="hidden" name="no_shipping" value="0">
			<input type="hidden" name="shipping" value="0">
			<input type="hidden" name="shipping2" value="0">
			<input type="hidden" name="handling" value="0.00">
			<input type="hidden" name="return" value="<?='reoc.zz.mu/paypal' ?>">
			<input type="hidden" name="notify_url" value="<?='reoc.zz.mu/paypal' ?>/ipn.php">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="custom" value="">
			<input type="hidden" name="currency_code" value="<?="U.S. Dollar" ?>">
			<input type="hidden" name="rm" value="2">
			<input type="submit" value="Paypal">
		</form>
		<script type="text/javascript">form1.submit();</script>
<?php 
	die();
?>