<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="pad_10tb">
	<div class="container">
		<div class="col-xs-12">
			<?=Form::errors()?>
			<div class="panel panel-default">
				<div class="panel-heading" id="page-edit-profile">
					<h3 class="panel-title"><?=_e('Edit Profile')?></h3>
				</div>

				<div class="panel-body">
					<div class="pad_10">
						<?= FORM::open(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')), array('class'=>'form', 'enctype'=>'multipart/form-data'))?>
							<div class="form-group clearfix">
								<?= FORM::label('name', _e('Name'), array('class'=>'col-xs-4 control-label', 'for'=>'name'))?>
								<div class="col-sm-8">
									<?= FORM::input('name', $user->name, array('class'=>'form-control', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
								</div>
							</div>
							<div class="form-group clearfix">
								<?= FORM::label('email', _e('Email'), array('class'=>'col-xs-4 control-label', 'for'=>'email'))?>
								<div class="col-sm-8">
									<?= FORM::input('email', $user->email, array('class'=>'form-control', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
								</div>
							</div>
							<div class="form-group clearfix">
								<?= FORM::label('description', _e('Description'), array('class'=>'col-xs-4 control-label', 'for'=>'description'))?>
								<div class="col-sm-8">
									<?= FORM::input('description', $user->description, array('class'=>'form-control', 'id'=>'description', 'type'=>'description' ,'placeholder'=>__('Description')))?>
								</div>
							</div>
							<?foreach($custom_fields as $name=>$field):?>
								<div class="form-group clearfix" id="cf_new">
									<?$cf_name = 'cf_'.$name?>
										<?if($field['type'] == 'select' OR $field['type'] == 'radio') {
											$select = array(''=>'');
											foreach ($field['values'] as $select_name) {
												$select[$select_name] = $select_name;
											}
										} else $select = $field['values']?>
											<?= FORM::label('cf_'.$name, $field['label'], array('class'=>'col-xs-4 control-label', 'for'=>'cf_'.$name))?>
											<div class="col-sm-8">
												<?=Form::cf_form_field('cf_'.$name, array(
												'display'   => $field['type'],
												'label'     => $field['label'],
												'tooltip'   => (isset($field['tooltip']))? $field['tooltip'] : "",
												'default'   => $user->$cf_name,
												'options'   => (!is_array($field['values']))? $field['values'] : $select,
												'required'  => $field['required'],
												))?>
											</div>
								</div>
							<?endforeach?>
							
							<div class="form-group clearfix">
								<div class="col-md-offset-4 col-md-8">
									<div class="checkbox">
										<label><input type="checkbox" name="subscriber" value="1" <?=($user->subscriber)?'checked':NULL?> > <?=_e('Subscribed to emails')?></label>
									</div>
								</div>
							</div>
							<div class="form-group clearfix">
								<div class="text-right">
									<button type="submit" class="btn btn-success"><?=_e('Update')?></button>
								</div>
							</div>
						<?= FORM::close()?>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading" id="page-edit-profile">
					<h3 class="panel-title"><?=_e('Change password')?></h3>
				</div>
				<div class="panel-body">
					<div class="pad_10">
						<form method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'changepass'))?>">         
										
						<div class="form-group clearfix">
							<label class="col-xs-4 control-label"><?=_e('New password')?></label>
							<div class="col-sm-8">
								<input class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-xs-4 control-label"><?=_e('Repeat password')?></label>
								<div class="col-sm-8">
									<input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
									<p class="help-block">
										<?=_e('Type your password twice to change')?>
									</p>
								</div>
						</div>
						<div class="form-group">
							<div class="text-right">
								<button type="submit" class="btn btn-success"><?=_e('Update')?></button>
							</div>
						</div>
						
						</form>
					</div>
				</div>
			</div>
	
			<form enctype="multipart/form-data" method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'image'))?>"> 
			<div class="panel panel-default">
					<div class="panel-heading" id="page-edit-profile">
					<h3 class="panel-title"><?=_e('Profile picture')?>
					
					<?if ($user->has_image):?>
						<button type="submit"
						class="btn btn-sm btn-danger index-delete index-delete-inline pull-right"
						onclick="return confirm('<?=__('Delete photo?')?>');" 
						type="submit" 
						name="photo_delete"
						value="1" 
						title="<?=__('Delete photo')?>">
						<span class="glyphicon glyphicon-remove"></span>
						</button>
					<?endif?>
					</h3>
				</div>
				<div class="panel-body">
					<div class="clearfix">
						<div class="col-sm-4  col-md-3 ">
							<div class="profile-pic">
								<a class="thumbnail">
									<img src="<?=$user->get_profile_image()?>" class="img-rounded" alt="<?=__('Profile Picture')?>" height='200px'>
								</a>
							</div>		
						</div>
				
						<div class="col-sm-8 col-md-9 clearfix">
							<?= FORM::label('profile_img', _e('Profile picture'), array('class'=>'col-xs-12 control-label', 'for'=>'profile_img'))?>
								<div class="col-sm-8">
									<input type="file" name="profile_image" id="profile_img" />
								</div>  
						</div>
					</div>
					<div class="text-right pad_10">
						<button type="submit" class="btn btn-success"><?=_e('Update')?></button>
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>	