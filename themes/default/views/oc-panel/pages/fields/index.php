<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'new'))?>" title="<?=__('New field')?>">
        <?=__('New field')?>
    </a>
    <h1><?=__('Custom Fields')?></h1>
    <?if (Theme::get('premium')!=1):?>
        <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
            <?=__('Custom fields are only available with premium themes!').'<br/>'.__('Upgrade your Open Classifieds site to activate this feature.')?>
            <a class="btn btn-success pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>" title="<?=__('Browse Themes')?>"><?=__('Browse Themes')?></a>
        </p>
    <?endif?>
    <a target='_blank' href='https://docs.yclas.com/how-to-create-custom-fields/'><?=__('Advertisement Custom Fields')?></a>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <ol class='plholder' id="ol_1" data-id="1">
                <?if (is_array($fields)):?>
                    <?foreach($fields as $name=>$field):?>
                        <li data-id="<?=$name?>" id="li_<?=$name?>">
                            <div class="drag-item">
                                <span class="drag-icon"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
                                <div class="drag-name">
                                    <?=$name?>
                                    <span class="label label-info "><?=$field['type']?></span>
                                    <span class="label label-info "><?=($field['searchable'])?__('searchable'):NULL?></span>
                                    <span class="label label-info "><?=($field['required'])?__('required'):NULL?></span>
                                    <span class="label label-info "><?=(isset($field['admin_privilege']) AND $field['admin_privilege'])?__('Only Admin'):NULL?></span>
                                    <span class="label label-info "><?=(isset($field['show_listing']) AND $field['show_listing'])?__('Show listing'):NULL?></span>
                                </div>
                                <a class="drag-action ajax-load" title="<?=__('Edit')?>"
                                    href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'update','id'=>$name))?>">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a 
                                    href="<?=Route::url('oc-panel', array('controller'=> 'fields', 'action'=>'delete','id'=>$name))?>" 
                                    class="drag-action index-delete" 
                                    title="<?=__('Are you sure you want to delete? All data contained in this field will be deleted.')?>" 
                                    data-id="li_<?=$name?>" 
                                    data-placement="left" 
                                    data-href="<?=Route::url('oc-panel', array('controller'=> 'fields', 'action'=>'delete','id'=>$name))?>" 
                                    data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                    data-btnCancelLabel="<?=__('No way!')?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </div>
                        </li>
                    <?endforeach?>
                <?endif?>
                </ol><!--ol_1-->
                <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'saveorder'))?>'></span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Custom Fields Templates')?></h3>
            </div>
            <div class="panel-body">
                <p><?=__('Create custom fields among predefined templates.')?></p>
                <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'template'))?>">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="date"><?=__('Type')?></label>      
                        <div class="col-sm-4">
                            <select name="type" class="form-control" id="cf_type_fileds" required>
                                <option value="cars"><?=__('Cars')?></option>
                                <option value="houses"><?=__('Real State')?></option>
                                <option value="jobs"><?=__('Jobs')?></option>
                                <option value="dating"><?=__('Friendship and Dating')?></option>  
                            </select>
                        </div>
                    </div>
                    <!-- multycategory selector -->
                    <div class="form-group">
                        <label class="control-label col-sm-2"><?=__('Categories')?></label>
                        <div class="col-sm-4">
                            <select id="categories" name="categories[]" multiple data-placeholder="<?=__('Choose 1 or several categories')?>">
                                <option></option>
                                <?function lili12($item, $key,$cats){?>
                                    <?if($cats[$key]['id'] != 1):?>
                                        <option value="<?=$cats[$key]['id']?>"><?=$cats[$key]['name']?></option>
                                    <?endif?>
                                    <?if (count($item)>0):?>
                                        <? if (is_array($item)) array_walk($item, 'lili12', $cats);?>
                                    <?endif?>
                                <?}array_walk($order_categories, 'lili12',$categories);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary"><?=__('Create')?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
