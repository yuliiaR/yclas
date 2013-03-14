<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Form Configuration')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-info"><?=__('Here are listed only form fields that are optional. To activate/deactiave select "TRUE/FALSE" in desired field. ')?></p>
		</div>

		<?= FORM::open(Route::url('oc-panel',array('controller'=>'formconf')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<div class="accordion" id="accordion2">
					<?foreach ($form_name as $element): ?>
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parrent="#accordion2" href='<?='#'.$element?>'>
									<h3><?=strtoupper(__($element))?><h3>
								</a>
							</div>
							<div id="<?=$element?>" class="accordion-body collapse">
								<?foreach($config as $elem => $val):?>
								<?$field_name = str_replace('-', '', strchr($val->config_key, '-'))?>
								<?if($element == strchr($val->config_key, '-', true)):?>	
								<div class="control-group">
									<?= FORM::label($field_name, __($field_name), array('class'=>'control-label', 'for'=>$field_name))?>
									<div class="controls">
										<?if($val->config_value == "TRUE" || $val->config_value == "FALSE"):?>
										
											<? $input = array("TRUE"=>"TRUE","FALSE"=>"FALSE");?>
											<?= FORM::select($element.'-'.$field_name, $input, $val->config_value, array(
											'placeholder' => $field_name, 
											'class' => 'input-xlarge', 
											'id' => $element.'-'.$field_name, 
											))?>
										<?else:?>
											<?= FORM::input($element.'-'.$field_name, $val->config_value, array('placeholder' => __($field_name), 'class' => 'input-xlarge', 'id' => $field_name, 'required'))?>
										<?endif?> 
									</div>
								</div>
								<?endif?>
								<?endforeach?>
							</div>
						</div>	
					<?endforeach?>
				</div> <!--accordion-->
				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'formconf'))))?>
				</div>
			</fieldset>
		<?= FORM::close()?>
	</div><!--end span 10-->
</div><!--end row-->