<?php defined('SYSPATH') or die('No direct script access.');?>
<?if (Auth::instance()->logged_in()):?>
    <?=View::factory('widget_notification')?>
    <div class="btn-group">
        <a class="btn btn-success" href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
            <i class="glyphicon glyphicon-user"></i> 
        </a>
        <button type="button" class="btn btn-success" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
                    <i class="glyphicon glyphicon-cog"></i> <?=__('Panel')?>
                </a>
            </li>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'myads','action'=>'index'))?>">
                    <i class="glyphicon glyphicon-edit"></i> <?=__('My Advertisements')?>
                </a>
            </li>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'favorites'))?>">
                    <i class="glyphicon glyphicon-heart"></i> <?=__('My Favorites')?>
                </a>
            </li>
            <?if(core::config('payment.paypal_seller') == TRUE OR Core::config('payment.stripe_connect')==TRUE):?>
            <li><a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'sales'))?>"><i
                   class="glyphicon glyphicon-usd"></i> <?=__('My Sales')?></a></li>
            <?endif?>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))?>">
                    <i class="glyphicon glyphicon-shopping-cart"></i> <?=__('My Payments')?>
                </a>
            </li>
            <?if (core::config('general.messaging') == TRUE):?>
                <li>
                    <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>">
                        <i class="fa fa-inbox"></i> <?=__('Messages')?>
                    </a>
                </li>
            <?endif?>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'subscriptions'))?>">
                    <i class="glyphicon glyphicon-envelope"></i> <?=__('Subscriptions')?>
                </a>
            </li>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>">
                    <i class="glyphicon glyphicon-lock"></i> <?=__('Edit profile')?>
                </a>
            </li>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'public'))?>">
                    <i class="glyphicon glyphicon-eye-open"></i> <?=__('Public profile')?>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
                    <i class="glyphicon glyphicon-off"></i> <?=__('Logout')?>
                </a>
            </li>
            <li>
                <a href="<?=Route::url('default')?>">
                    <i class="glyphicon glyphicon-home"></i> <?=__('Visit Site')?>
                </a>
            </li>
        </ul>
    </div>
<?else:?>
    <a class="btn btn-default" data-toggle="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
        <i class="glyphicon glyphicon-user"></i> <?=__('Login')?>
    </a>
<?endif?>