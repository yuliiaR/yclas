<p class="text-center">
	<b><?=_e('Bitcoin Address')?></b>
	<br>
	<?if(isset($ad->cf_bitcoinaddress) AND !empty($ad->cf_bitcoinaddress)):?>
		<img title="Bitcoin QR code for the address <?=$ad->cf_bitcoinaddress?>" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?=$ad->cf_bitcoinaddress?>">	
		<br>
		<b><?=$ad->cf_bitcoinaddress?></b>
	<?elseif(isset($ad->user->cf_bitcoinaddress) AND !empty($ad->user->cf_bitcoinaddress)):?>
		<img title="Bitcoin QR code for the address <?=$ad->user->cf_bitcoinaddress?>" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?=$ad->user->cf_bitcoinaddress?>">	
		<br>
		<b><?=$ad->user->cf_bitcoinaddress?></b>
	<?endif?>
</p>
