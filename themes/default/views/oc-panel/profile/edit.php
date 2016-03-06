<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row">
    <div class="col-md-12">
        <?if( Core::config('payment.stripe_connect')==1):?>
            <div class="panel panel-default">
                <div class="panel-heading" id="page-edit-profile">
                    <h3 class="panel-title"><?=__('Stripe Connect')?></h3>
                    <p><?=sprintf(__('Sell your items with credit card using stripe. Our platform charges %s percentage, per transaction.'),Core::config('payment.stripe_appfee'))?></p>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <?if ($user->stripe_user_id!=''):?>
                                Stripe connected <?=$user->stripe_user_id?>
                                <br>
                                Reconnect:
                                <br>
                            <?endif?>
                            <a class="btn btn-primary" href="<?=Route::url('default', array('controller'=>'stripe','action'=>'connect','id'=>'now'))?>">
                                <span class="glyphicon glyphicon-usd" aria-hidden="true"></span> Connect with Stripe
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
        <?endif?>

        <div class="panel panel-default">
            <div class="panel-heading" id="page-edit-profile">
                <h3 class="panel-title"><?=__('Edit Profile')?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <?= FORM::open(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
                            <div class="form-group">
                                <?= FORM::label('name', __('Name'), array('class'=>'col-xs-4 control-label', 'for'=>'name'))?>
                                <div class="col-sm-8">
                                    <?= FORM::input('name', $user->name, array('class'=>'form-control', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= FORM::label('email', __('Email'), array('class'=>'col-xs-4 control-label', 'for'=>'email'))?>
                                <div class="col-sm-8">
                                    <?= FORM::input('email', $user->email, array('class'=>'form-control', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= FORM::label('description', __('Description'), array('class'=>'col-xs-4 control-label', 'for'=>'description'))?>
                                <div class="col-sm-8">
                                    <?= FORM::input('description', $user->description, array('class'=>'form-control', 'id'=>'description', 'type'=>'description' ,'placeholder'=>__('Description')))?>
                                </div>
                            </div>
        
                            <?foreach($custom_fields as $name=>$field):?>
                                <div class="form-group" id="cf_new">
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
        
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-8">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="subscriber" value="1" <?=($user->subscriber)?'checked':NULL?> > <?=__('Subscribed to emails')?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
                                </div>
                            </div>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" id="page-edit-profile">
                <h3 class="panel-title"><?=__('Change password')?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'changepass'))?>">         
                            <?=Form::errors()?>  
                                  
                            <div class="form-group">
                                <label class="col-xs-4 control-label"><?=__('New password')?></label>
                                <div class="col-sm-8">
                                <input class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
                                </div>
                            </div>
                              
                            <div class="form-group">
                                <label class="col-xs-4 control-label"><?=__('Repeat password')?></label>
                                <div class="col-sm-8">
                                <input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
                                    <p class="help-block">
                                          <?=__('Type your password twice to change')?>
                                    </p>
                                </div>
                            </div>
                                  
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
                                </div>
                            </div>
                                  
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" id="page-edit-profile">
                <h3 class="panel-title"><?=__('Profile picture')?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-offset-4 col-md-3">
                                <a class="thumbnail">
                                    <img src="<?=$user->get_profile_image()?>" class="img-rounded" alt="<?=__('Profile Picture')?>" height='200px'>
                                </a>
                            </div>
                        </div>
                        <form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'image'))?>">         
                                <?=Form::errors()?>  
                              
                              <div class="form-group">
                                <?= FORM::label('profile_img', __('Profile picture'), array('class'=>'col-xs-4 control-label', 'for'=>'profile_img'))?>
                                <div class="col-sm-8">
                                    <input type="file" name="profile_image" id="profile_img" />
                                </div>  
                              </div>
                              
                              <div class="form-group">
                                  <div class="col-md-offset-4 col-md-8">
                                      <button type="submit" class="btn btn-primary"><?=__('Update')?></button>
                                      
                                      <?if ($user->has_image):?>
                                          <button type="submit"
                                                  class="btn btn-danger index-delete index-delete-inline"
                                                  onclick="return confirm('<?=__('Delete photo?')?>');" 
                                                  type="submit" 
                                                  name="photo_delete"
                                                  value="1" 
                                                  title="<?=__('Delete photo')?>">
                                                  <?=__('Delete photo')?>
                                          </button>
                                      <?endif?>
                                  </div>
                              </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?if( Core::config('general.subscriptions')==1):?>
            <div class="panel panel-default">
                <div class="panel-heading" id="page-edit-profile">
                    <h3 class="panel-title"><?=__('Subscription')?></h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <?if ($user->subscription()->loaded()):?>
                                <p><?=sprintf(__('You are subscribed to the plan %s until %s with %u ads left'),$user->subscription()->plan->name,$user->subscription()->expire_date,$user->subscription()->amount_ads_left)?></p>
                            <?else:?>
                                <a class="btn btn-primary" href="<?=Route::url('pricing')?>">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?=__('Choose a Plan')?>
                                </a>
                            <?endif?>
                        </div>
                    </div>
                </div>
            </div>
        <?endif?>
        
    </div>
</div>
<!--/row-->
