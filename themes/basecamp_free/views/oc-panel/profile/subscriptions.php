<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pad_10tb">
	<div class="container">
		<div class="col-xs-12">
			<div class="page-header">
				<h3>
					<?=__('Subscriptions')?>
				</h3>
			</div>

			<?=Alert::show()?>
			<?=Form::errors()?>
			<div class="pad_10 text-right">
				<a
					href="<?=Route::url('default', array('controller'=>'subscribe','action'=>'unsubscribe', 'id'=>Auth::instance()->get_user()->id_user))?>" 
					class="btn btn-danger" 
					title="<?=__('Unsubscribe to all?')?>" 
					data-toggle="confirmation" 
					data-placement="top" 
					data-href="<?=Route::url('default', array('controller'=>'subscribe','action'=>'unsubscribe', 'id'=>Auth::instance()->get_user()->id_user))?>" 
					data-btnOkLabel="<?=__('Yes, definitely!')?>" 
					data-btnCancelLabel="<?=__('No way!')?>">
					<i class="glyphicon glyphicon-remove"></i> <?=__('Unsubscribe to all?')?>
				</a>
			</div>	

			<div class="panel panel-default table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><?=__('Category')?></th>
							<th><?=__('Location')?></th>
							<th><?=__('Price')?></th>
							<th class="hidden-xs"><?=__('Created')?></th>
							<th width="55">
								&nbsp;
							</th>
						</tr>
					</thead>
					<tbody>
						<?foreach($list as $l):?>
							<tr>
								<td style="vertical-align:middle;">
									<!-- category -->
									<?if ($l['category']):?>
										<?=$l['category']?>
									<?else:?>
										N/A
									<?endif?>
								</td>
								<td style="vertical-align:middle;">
									<!-- locations -->
									<?if ($l['location']):?>
										<?=$l['location']?>
									<?else:?>
										N/A
									<?endif?>
								</td>
								<td style="vertical-align:middle;">
									<!-- Min price -->
									<span class="badge min-price"><?=i18n::money_format($l['min_price'])?></span>
										&nbsp;-&nbsp;
									<!-- Max Price -->
									<span class="badge max-price"><?=i18n::money_format($l['max_price'])?></span>
								</td>
								<td style="vertical-align:middle;" class="hidden-xs ">
									<!-- Created -->
									<?=substr($l['created'], 0, 11)?>
								</td>
								<td class="text-center">
									<!-- unsubscribe one entry button -->
									<a
										href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'unsubscribe','id'=>$l['id']))?>" 
										class="btn btn-sm btn-danger" 
										title="<?=__('Unsubscribe?')?>" 
										data-toggle="confirmation" 
										data-btnOkLabel="<?=__('Yes, definitely!')?>" 
										data-btnCancelLabel="<?=__('No way!')?>">
										<i class="glyphicon glyphicon-remove"></i>
									</a>
								</td>
							</tr>
						<?endforeach?>
					</tbody>
				</table>
			</div>

		</div>
	</div>
</div>