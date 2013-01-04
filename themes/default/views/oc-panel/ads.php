<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="hero-unit">
	<h1>
	<?=__('Ads')?>
	</h1>
	<a class="btn btn-primary pull-right" href="<?=Route::url('post_new')?>">
		<i class="icon-pencil icon-white"></i>New
	</a>

	
</div>

	<?foreach($ads as $p ):?>
		<p><? echo $p->title; ?></p>
		<a class="btn btn-primary" href="" title="Edit">
			<i class="icon-edit icon-white"></i>
		</a>
		<a class="btn btn-danger index-delete" href="" title="Delete" data-id="tr1" data-text="Are you sure you want to delete?">
			<i class="icon-remove icon-white"></i>
		</a>
	<?endforeach?>
	 <?=$pagination?>
