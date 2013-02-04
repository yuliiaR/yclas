<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?echo $page->title?></h1>
		</div>
		<div class="control-group">
			<div>
				<? echo $page->description; ?>
			</div>
		<div>
	</div>
</div>