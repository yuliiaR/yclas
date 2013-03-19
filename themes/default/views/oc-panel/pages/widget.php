<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Widgets')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-info"><?=__('You can drag and drop any widget to make it active. NOTE: Some widgets are placeholder specific')?></p>
		</div>
		<?= print_r($placeholder)?>
		<?foreach($placeholder as $p => $value):?>
			<div class='control-group'>
				<h3><?=$value?></h3>
				
			</div>
		<?endforeach?>
	</div><!--end span10-->
</div><!--end row-fluid -->