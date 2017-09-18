<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row">
    <div class="col-md-12">
        <?if( Core::config('payment.stripe_connect')==1):?>
            <div class="panel panel-default">
                <div class="panel-heading" id="page-edit-profile">
                    <h3 class="panel-title"><?=_e('Stripe Connect')?></h3>
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
                <h3 class="panel-title"><?=_e('Edit Profile')?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <?= FORM::open(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
                            <div class="form-group">
                                <?= FORM::label('name', _e('Name'), array('class'=>'col-xs-4 control-label', 'for'=>'name'))?>
                                <div class="col-sm-8">
                                    <?= FORM::input('name', $user->name, array('class'=>'form-control', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= FORM::label('email', _e('Email'), array('class'=>'col-xs-4 control-label', 'for'=>'email'))?>
                                <div class="col-sm-8">
                                    <?= FORM::input('email', $user->email, array('class'=>'form-control', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?if (core::config('general.sms_auth')==TRUE):?>
                                    <label class="col-xs-4 control-label"><?=_e('Mobile phone number')?></label>
                                <?else:?>
                                    <?=FORM::label('phone', _e('Phone'), array('class'=>'col-xs-4 control-label', 'for'=>'phone'))?>
                                <?endif?>
                                <div class="col-sm-8">
                                    <?= FORM::input('phone', $user->phone, array('class'=>'form-control', 'id'=>'phone', 'type'=>'phone' ,'required', 'placeholder'=>__('Phone'), 'data-country' => core::config('general.country')))?>
                                    <?if (core::config('general.sms_auth')==TRUE):?>
                                        <span class="help-block"><?=_e('Used for SMS authentication.')?></span>
                                    <?endif?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= FORM::label('description', _e('Description'), array('class'=>'col-xs-4 control-label', 'for'=>'description'))?>
                                <div class="col-sm-8">
                                    <?=FORM::textarea('description', $user->description, array(
                                    'placeholder' => '',
                                    'rows' => 3, 'cols' => 50,
                                    'class' => 'form-control',
                                    'id' => 'description',
                                ))?>
                                </div>
                            </div>

                            <?foreach($custom_fields as $name=>$field):?>
                                <?if($name!='verifiedbadge' OR Auth::instance()->get_user()->is_admin() OR Auth::instance()->get_user()->is_moderator()):?>
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
                                <?endif?>
                            <?endforeach?>

                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-8">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="subscriber" value="1" <?=($user->subscriber)?'checked':NULL?> > <?=_e('Subscribed to emails')?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="submit" class="btn btn-primary"><?=_e('Update')?></button>
                                </div>
                            </div>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" id="page-edit-profile">
                <h3 class="panel-title"><?=_e('Change password')?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'changepass'))?>">
                            <?=Form::errors()?>

                            <div class="form-group">
                                <label class="col-xs-4 control-label"><?=_e('New password')?></label>
                                <div class="col-sm-8">
                                <input class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label"><?=_e('Repeat password')?></label>
                                <div class="col-sm-8">
                                <input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
                                    <p class="help-block">
                                          <?=_e('Type your password twice to change')?>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="submit" class="btn btn-primary"><?=_e('Update')?></button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?if( Core::config('general.google_authenticator')==TRUE):?>
        <div class="panel panel-default">
            <div class="panel-heading" id="page-edit-profile">
                <h3 class="panel-title"><?=_e('2 Step Authentication')?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <?if ($user->google_authenticator!=''):?>
                            <p><img src="<?=$user->google_authenticator_qr()?>"></p>
                            <p><?=_e('Google Authenticator Code')?>: <?=$user->google_authenticator?></p>
                            <p>
                                <a class="btn btn-warning" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'2step','id'=>'disable'))?>">
                                    <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> <?=_e('Disable')?>
                                </a>
                            </p>
                        <?else:?>
                            <p>
                                <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'2step','id'=>'enable'))?>">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?=_e('Enable')?>
                                </a>
                            </p>
                        <?endif?>
                        <hr>
                        <p><?=_e('2 step authentication provided by Google Authenticator.')?></p>
                        <div class="btn-group">
                            <a class="btn btn-default" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"><i class="fa fa-android"></i> Android</a>
                            <a class="btn btn-default" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8"><i class="fa fa-apple"></i> iOS</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?endif?>

        <div class="panel panel-default">
            <div class="panel-heading" id="page-edit-profile">
                <h3 class="panel-title"><?=_e('Profile pictures')?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form enctype="multipart/form-data" class="upload_image" method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'image'))?>">
                            <?=Form::errors()?>
                            <div class="form-group images"
                                data-max-image-size="<?=core::config('image.max_image_size')?>"
                                data-image-width="<?=core::config('image.width')?>"
                                data-image-height="<?=core::config('image.height') ? core::config('image.height') : 0?>"
                                data-image-quality="<?=core::config('image.quality')?>"
                                data-swaltext="<?=sprintf(__('Is not of valid size. Size is limited to %s MB per image'),core::config('image.max_image_size'))?>">
                                <?$images = $user->get_profile_images()?>
                                <?if($images):?>
                                    <div class="row">
                                        <?foreach ($images as $key => $image):?>
                                            <div id="img<?=$key?>" class="col-md-4 edit-image">
                                                <a><img src="<?=$image?>" class="img-rounded thumbnail img-responsive"></a>
                                                <?if ($key > 0) :?>
                                                    <button class="btn btn-danger index-delete img-delete"
                                                            data-title="<?=__('Are you sure you want to delete?')?>"
                                                            data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                            data-btnCancelLabel="<?=__('No way!')?>"
                                                            type="submit"
                                                            name="img_delete"
                                                            value="<?=$key?>"
                                                            href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'image'))?>">
                                                            <?=_e('Delete')?>
                                                    </button>
                                                <?endif?>
                                                <?if ($key > 1) :?>
                                                    <button class="btn btn-info img-primary"
                                                        type="submit"
                                                        name="primary_image"
                                                        value="<?=$key?>"
                                                        href="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'image'))?>"
                                                        action="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'image'))?>"
                                                    >
                                                            <?=_e('Primary image')?>
                                                    </button>
                                                <?endif?>
                                            </div>
                                        <?endforeach?>
                                    </div>
                                <?endif?>
                            </div>
                            <?if (core::config('advertisement.num_images') > count($images)):?>
                                <hr>
                                <div class="form-group">
                                    <h5><?=_e('Add image')?></h5>
                                    <div>
                                        <?for ($i = 0; $i < (core::config('advertisement.num_images') - count($images)); $i++):?>
                                            <div class="fileinput fileinput-new <?=($i >= 1) ? 'hidden' : NULL?>" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                                <div>
                                                    <span class="btn btn-default btn-file">
                                                        <span class="fileinput-new"><?=_e('Select')?></span>
                                                        <span class="fileinput-exists"><?=_e('Edit')?></span>
                                                        <input type="file" name="<?='image'.$i?>" id="<?='fileInput'.$i?>" accept="<?='image/'.str_replace(',', ', image/', rtrim(core::config('image.allowed_formats'),','))?>">
                                                    </span>
                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"><?=_e('Delete')?></a>
                                                </div>
                                            </div>
                                        <?endfor?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><?=_e('Upload')?></button>
                                </div>
                            <?endif?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?if( Core::config('general.subscriptions')==1):?>
            <div class="panel panel-default">
                <div class="panel-heading" id="page-edit-profile">
                    <h3 class="panel-title"><?=_e('Subscription')?></h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <?if ($user->subscription()->loaded()):?>
                                <p>
                                    <?if($user->subscription()->amount_ads_left > -1 ):?>
                                        <?=sprintf(__('You are subscribed to the plan %s until %s with %u ads left'),$user->subscription()->plan->name,$user->subscription()->expire_date,$user->subscription()->amount_ads_left)?>
                                    <?else:?>
                                        <?=sprintf(__('You are subscribed to the plan %s until %s with unlimited ads'),$user->subscription()->plan->name,$user->subscription()->expire_date)?>
                                    <?endif?>
                                </p>
                                <?if ($user->stripe_agreement!=NULL):?>
                                    <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'cancelsubscription'))?>"
                                              class="btn btn-danger"
                                              onclick="return confirm('<?=__('Cancel Subscription?')?>');"
                                              title="<?=__('Cancel Subscription')?>">
                                              <?=_e('Cancel Subscription')?>
                                    </a>
                                <?endif?>
                            <?else:?>
                                <a class="btn btn-primary" href="<?=Route::url('pricing')?>">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?=_e('Choose a Plan')?>
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
