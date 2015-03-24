<?php defined('SYSPATH') or die('No direct script access.');?>

<div id="page-categories" class="page-header">
    <a class="btn btn-primary ajax-load pull-right" href="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'create'))?>" title="<?=__('New Category')?>">
        <?=__('New Category')?>
    </a>
    <h1><?=__('Categories')?></h1>
    <p><?=__("Change the order of your categories. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).")." <a target='_blank' href='http://open-classifieds.com/2013/08/12/how-to-add-categories/'>".__('Read more')."</a>"?></p>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-body">
                <ol class='plholder' id="ol_1" data-id="1">
                    <?function lili($item, $key,$cats){?>
                        <li data-id="<?=$key?>" id="li_<?=$key?>">
                            <div class="drag-item">
                                <span class="drag-icon"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
                                <div class="drag-name">
                                    <?=$cats[$key]['name']?>
                                </div>
                                <a class="drag-action ajax-load" title="<?=__('Edit')?>"
                                    href="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'update','id'=>$key))?>">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a 
                                    href="<?=Route::url('oc-panel', array('controller'=> 'category', 'action'=>'delete','id'=>$key))?>" 
                                    class="drag-action index-delete" 
                                    title="<?=__('Are you sure you want to delete?')?>" 
                                    data-id="li_<?=$key?>" 
                                    data-text="<?=__('We will move the siblings categories and ads to the parent of this category.')?>"
                                    data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                    data-btnCancelLabel="<?=__('No way!')?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </div>
                
                            <ol data-id="<?=$key?>" id="ol_<?=$key?>">
                                <? if (is_array($item)) array_walk($item, 'lili', $cats);?>
                            </ol><!--ol_<?=$key?>-->
                
                        </li><!--li_<?=$key?>-->
                    <?}
                    if(is_array($order))
                        array_walk($order, 'lili',$cats);?>
                </ol><!--ol_1-->
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-body">
                <span class="label label-info"><?=__('Heads Up!')?> <?=__('Quick category creator.')?></span>
                <div class="clearfix"></div> 
                <?=__('Add names for multiple categories, for each one push enter.')?>
                <div class="clearfix"></div><br>

                <?= FORM::open(Route::url('oc-panel',array('controller'=>'category','action'=>'multy_categories')), array('class'=>'form-inline', 'role'=>'form','enctype'=>'multipart/form-data'))?>
                    <div class="form-group">
                        <div class="">
                        <?= FORM::label('multy_categories', __('Name').':', array('class'=>'control-label', 'for'=>'multy_categories'))?>
                        <?= FORM::input('multy_categories', '', array('placeholder' => __('Hit enter to confirm'), 'class' => 'form-control', 'id' => 'multy_categories', 'type' => 'text','data-role'=>'tagsinput'))?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?= FORM::button('submit', __('Send'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'category','action'=>'multy_categories'))))?>
                <?= FORM::close()?>
            </div>
        </div>
    </div>
</div>