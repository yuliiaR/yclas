<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->text_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->text_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <h4><?=$widget->user->name?></h4>
    <?if (Core::config('advertisement.reviews')==1):?>
        <p>
            <?for ($j=0; $j < round($widget->user->rate,1); $j++):?>
                <span class="glyphicon glyphicon-star"></span>
            <?endfor?>
        </p>
    <?endif?>
    <a title="<?=__('Visit profile')?>" href="<?=Route::url('profile',  array('seoname'=>$widget->user->seoname))?>" class="thumbnail">
        <img class="img-responsive center-block" src="<?=Core::imagefly($widget->user->get_profile_image(),200,200)?>" alt="<?=__('Profile Picture')?>">
    </a>
    <p>
        <a title="<?=__('Visit profile')?>" href="<?=Route::url('profile',  array('seoname'=>$widget->user->seoname))?>">
            <span class="badge badge-light"><?=$widget->user_ads?> <?=_e('Ads')?></span> - <?=_e('See All')?>
        </a>
    </p>
    <?if($widget->description):?>
        <p><?=$widget->user->description?></p>
    <?endif?>
    <?if($widget->last_login):?>
        <p><b><?=_e('Last Login')?>:</b> <?=$widget->user->last_login?></p>
    <?endif?>
    <?if($widget->custom_fields AND Theme::get('premium')==1):?>
        <p>
            <ul class="list-unstyled">
                <?foreach ($widget->user->custom_columns(TRUE) as $name => $value):?>
                    <?if($value!=''):?>
                        <?if($name!='Whatsapp' AND $name!='Skype' AND $name!='Telegram'):?>
                            <li>
                                <strong><?=$name?>:</strong>
                                <?if($value=='checkbox_1'):?>
                                    <i class="fa fa-check"></i>
                                <?elseif($value=='checkbox_0'):?>
                                    <i class="fa fa-times"></i>
                                <?else:?>
                                    <?=$value?>
                                <?endif?>
                            </li>
                        <?endif?>
                    <?endif?>
                <?endforeach?>
            </ul>
            <?if(isset($widget->user->cf_whatsapp) AND $widget->user->cf_whatsapp!=''):?>
                <a href="https://api.whatsapp.com/send?phone=<?=$widget->user->cf_whatsapp?>" title="Chat with <?=$widget->user->name?>" alt="Whatsapp"><i class="fa fa-2x fa-whatsapp" style="color:#43d854"></i></a>
            <?endif?>
            <?if(isset($widget->user->cf_skype) AND $widget->user->cf_skype!=''):?>
                <a href="skype:<?=$widget->user->cf_skype?>?chat" title="Chat with <?=$widget->user->name?>" alt="Skype"><i class="fa fa-2x fa-skype" style="color:#00aff0"></i></a>
            <?endif?>
            <?if(isset($widget->user->cf_telegram) AND $widget->user->cf_telegram!=''):?>
                <a href="tg://resolve?domain=<?=$widget->user->cf_telegram?>" id="telegram" title="Chat with <?=$widget->user->name?>" alt="Telegram"><i class="fa fa-2x fa-telegram" style="color:#0088cc"></i></a>
            <?endif?>
        </p>
    <?endif?>

    <?if ($widget->ad->can_contact() AND $widget->contact):?>
        <p>
            <?if ((core::config('advertisement.login_to_contact') == TRUE OR core::config('general.messaging') == TRUE) AND !Auth::instance()->logged_in()) :?>
                <a class="form-control btn btn-success" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                    <i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;<?=_e('Send Message')?>
                </a>
            <?else :?>
                <button class="form-control btn btn-success" type="button" data-toggle="modal" data-target="#contact-modal"><i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;<?=_e('Send Message')?></button>
            <?endif?>

        </p>
    <?endif?>

</div>
