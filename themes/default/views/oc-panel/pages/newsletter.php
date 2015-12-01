<?php defined('SYSPATH') or die('No direct script access.');?>

<?=View::factory('oc-panel/elasticemail')?>

<?if (Theme::get('premium')!=1):?>
    <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
        <?=__('Only if you have a premium theme you will be able to filter by users!').'<br/>'.__('Upgrade your Open Classifieds site to activate this feature.')?>
        <a class="btn btn-success pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>" title="<?=__('Browse Themes')?>"><?=__('Browse Themes')?></a>
    </p>
<?endif?>

<div class="page-header">
    <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'settings','action'=>'email'))?>?force=1" title="<?=__('Email Settings')?>">
        <?=__('Email Settings')?>
    </a>
    <h1><?=__('Newsletter')?> <small><a href="https://docs.yclas.com/how-to-send-the-newsletter/" target="_blank"><?=__('Read more')?></a></small></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <form method="post" action="<?=Route::url('oc-panel',array('controller'=>'newsletter','action'=>'index'))?>">  
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-horizontal">  
                        <?=Form::errors()?> 
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__('To')?>:</label>
                            <div class="col-md-8">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="send_all" checked >
                                        <?=__('All active users.')?> <span class="badge badge-info"><?=$count_all_users?></span>
                                    </label>
                                </div> 
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="send_featured"  >
                                        <?=__('Users with featured ads.')?> <span class="badge badge-info"><?=$count_featured?></span>
                                    </label>
                                </div> 
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="send_featured_expired"  >
                                        <?=__('Users with featured ads expired.')?> <span class="badge badge-info"><?=$count_featured_expired?></span>
                                    </label>
                                </div> 
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="send_unpub"  >
                                        <?=__('Users without published ads.')?> <span class="badge badge-info"><?=$count_unpub?></span>
                                    </label>
                                </div> 
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="send_logged"  >
                                        <?=__('Users not logged last 3 months')?> <span class="badge badge-info"><?=$count_logged?></span>
                                    </label>
                                </div> 
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="send_spam"  >
                                        <?=__('Users marked a spam')?> <span class="badge badge-info"><?=$count_spam?></span>
                                    </label>
                                </div> 
                            </div> 
                        </div>
                       
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__('From')?>:</label>
                            <div class="col-md-8">
                                <input  type="text" name="from" value="<?=Auth::instance()->get_user()->name?>" class="form-control"  />
                            </div>
                        </div>
                
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__('From Email')?>:</label>
                            <div class="col-md-8">
                                <input  type="text" name="from_email" value="<?=Auth::instance()->get_user()->email?>" class="form-control"  />
                            </div>
                        </div>
                
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__('Subject')?>:</label>
                            <div class="col-md-8">
                                <input  type="text" name="subject" value="" class="form-control"  />
                            </div>
                        </div>
                
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__('Message')?>:</label>
                            <div class="col-md-8">
                                <textarea  name="description"  id="formorm_description" class="col-md-10 col-sm-10 col-xs-12 form-control" data-editor="html" rows="15" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-8 col-sm-offset-4">
                        <a href="<?=Route::url('oc-panel')?>" class="btn btn-default"><?=__('Cancel')?></a>
                        <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>