<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Widgets')?></h1>
		</div>

		<ul class="breadcrumb">
				<li><a href="<?=Route::url('oc-panel',array('controller'=>'widget'))?>"><?=__('Placeholders')?></a><span class="divider">&raquo;</span></li>
			<?foreach($placeholders as $plh => $value):?>
				<li><a href="<?=Route::url('oc-panel',array('controller'=>'widget', 'action'=>'index', 'id'=>$value))?>"><?=$value?></a><span class="divider">&raquo;</span></li>
			<?endforeach?>
			</ul>
		<?if($placeholder_name == NULL):?>

			<table class="placeholder-table table table-striped table-bordered">
					<thead>
						<tr>
							<th><?=__('Placeholder')?></th>
							<th><?=__('Active Widgets')?></th>
						</tr>
					</thead>
					<tbody id="drop-here">
						<?foreach($active_widgets as $aw => $value):?>
						<?if($value !== NULL):?>
						<tr>
							<td><?=$aw?></td>
							<td>
								<?foreach ($value as $v):?>
								<?=print_r($basic_info[$v]['deactivate_placeholder'])?>
									<?if(!empty($v) && !in_array($aw, $basic_info[$v]['deactivate_placeholder'])):?>
									<div class="well advise clearfix">
										<h4><?=$basic_info[$v]['title']?></h4>​
										<p><small><?=$basic_info[$v]['short_description']?></small></p>
									</div>
									<div class="btn-group">
										<a class="btn btn-mini" href="<?=Route::url('oc-panel', array('controller'=>'widget', 'action'=>'remove', 'id'=>"?$v=$aw"))?>">Remove</a>
									</div>
									<hr>
									<?endif?>
								<?endforeach?>
							</td>
						</tr>
						<?endif?>
						<?endforeach?>
					</tbody>
				<table>
		<?else:?>

		<div class="well advise clearfix">
			<p class="text-info"><?=__('You can drag and drop any widget to make it active. NOTE: Some widgets are placeholder specific')?></p>
		</div>
		<div class="row-fluid show-grid">
			
			<?if($placeholder_name !== NULL):?>
			<div class="">
				<table class="placeholder-table table table-striped table-bordered">
					<thead>
						<tr>
							<th><?=__('Placeholder')?></th>
							<th><?=__('Active')?></th>
							<th><?=__('Drop Here')?></th>
						</tr>
					</thead>
					<tbody id="drop-here">
						<tr>
							<td><?=$placeholder_name?></td>
							
							<td>
								
								<?foreach($active_widgets as $aw => $value):?>
								<?if($placeholder_name == $aw && $value !== NULL):?>
									<?foreach ($value as $v):?>
										<?if(!empty($v)):?>
										<div class="well advise clearfix">
											<h4><?=$v?></h4>​
										</div>
										<div class="btn-group">
											<a class="btn btn-mini" href="<?=Route::url('oc-panel', array('controller'=>'widget', 'action'=>'remove', 'id'=>"?$v=$aw"))?>">Remove</a>
										</div>
										<hr>
										<?endif?>
									<?endforeach?>
								<?endif?>
								<?endforeach?>
								
							</td>
						
							<td style="position:relative"><div class="well makeMeDroppable" style="border:2px dotted black; position:absolute; bottom:0px; width:73%; height:10%;"></div></td>
						</tr>
					</tbody>
				<table>
			</div>
			<?endif?>
			<div class="">
				<table class="widget-table table table-striped table-bordered">
					<thead>
						<tr>
							<th><?=__('Widget')?></th>
							<th><?=__('Activate')?></th>
						</tr>
					</thead>
					<tbody>
						<?foreach($default_widgets as $dw => $value):?>
						<?//if($dw)?>
							<tr>
								<td><?=$value?></td>
								<td><div id="<?=$value?>" class="well advise makeMeDraggable" name="<?=$placeholder_name?>"><?=$value?></div></td>
							</tr>
						<?endforeach?>
					</tbody>
				<table>
			</div>
		</div>
		<?endif?>
	</div><!--end span10-->
</div><!--end row-fluid -->