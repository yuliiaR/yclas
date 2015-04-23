<?php defined('SYSPATH') or die('No direct script access.');?>
<hr>

<footer>
<!--This is the license for Open Classifieds, do not remove -->
<p>&copy;
<?if (Theme::get('premium')!=1):?>
    Web Powered by <a href="http://open-classifieds.com?utm_source=<?=URL::base()?>&utm_medium=oc_footer&utm_campaign=<?=date('Y-m-d')?>" title="Best PHP Script Classifieds Software">Open Classifieds</a> 
    2009 - <?=date('Y')?>
<?else:?>
    <?=core::config('general.site_name')?> <?=date('Y')?>
<?endif?>    

</p>
</footer>
<?if (Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN AND Core::config('general.subscribe')==0) :?>
    <div class="modal fade" id="subscribeModal" tabindex="-1" role="dialog" aria-labelledby="subscribeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="alert alert-info" role="alert"><i class="fa fa-lightbulb-o"></i> Hi! Subcribe to Open Classifieds newsletter, you will get a full guide on PDF, some tips and news about software updates. We will never spam you!.</div>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="email"><?=__('Email')?></label>
                            <div class="col-md-6 input-group">
                                <input type="email" class="form-control" id="email" name="email" value="<?=Auth::instance()->get_user()->email?>" disabled>
                                <span class="input-group-addon">@</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="button" id="subscribe-cancel" class="btn btn-default btn-xs pull-left" data-url="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'subscribe'))?>"><?=__('No')?></button>
                        <button type="button" id="subscribe-accept" class="btn btn-success" data-email="<?=Auth::instance()->get_user()->email?>" data-url="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'subscribe'))?>"><?=__('Yes, Keep me updated!')?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif?>
<div class="modal fade" id="docModal" tabindex="-1" role="dialog" aria-labelledby="docModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>