<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header" id="crud-<?=__($name)?>">
    <h1><?=__('Update')?> <?=ucfirst(__($name))?></h1>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Category details')?></h3>
            </div>
            <div class="panel-body">
                  <?=$form->render()?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Upload category icon')?></h3>
            </div>
            <div class="panel-body">
                <?if (( $icon_src = $category->get_icon() )!==FALSE ):?>
                    <div class="row">
                        <div class="col-md-4">
                            <a class="thumbnail">
                                <img src="<?=$icon_src?>" class="img-rounded" alt="<?=__('Category icon')?>" height='200px'>
                            </a>
                        </div>
                    </div>
                <?endif?>
                <form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'icon','id'=>$form->object->id_category))?>">         
                    <?=Form::errors()?>  
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?= FORM::label('category_icon', __('Select from files'), array('for'=>'category_icon'))?>
                            <input type="file" name="category_icon" class="form-control" id="category_icon" />
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?=__('Submit')?></button> 
                    <?if (( $icon_src = $category->get_icon() )!==FALSE ):?>
                        <button type="submit"
                            class="btn btn-danger index-delete index-delete-inline"
                             onclick="return confirm('<?=__('Delete icon?')?>');" 
                             type="submit" 
                             name="icon_delete"
                             value="1" 
                             title="<?=__('Delete icon')?>">
                            <?=__('Delete icon')?>
                        </button>
                    <?endif?>
                </form>
            </div>
        </div>
        <?if (Theme::get('premium')==1):?>
            <?if (is_array($fields = Model_Field::get_by_category($category->id_category))):?>
                <div class="panel panel-default">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?=__('Custom Fields')?></h3>
                        </div>
                        <div class="panel-body">
                            <ol class='plholder' id="ol_1" data-id="1">
                                    <?foreach($fields as $name=>$field):?>
                                        <li class="inactive" data-id="<?=$name?>" id="li_<?=$name?>">
                                            <div class="drag-item">
                                                <div class="drag-name">
                                                    <?=$name?>
                                                    <span class="label label-info "><?=$field['type']?></span>
                                                    <span class="label label-info "><?=($field['searchable'])?__('searchable'):NULL?></span>
                                                    <span class="label label-info "><?=($field['required'])?__('required'):NULL?></span>
                                                    <span class="label label-info "><?=(isset($field['admin_privilege']) AND $field['admin_privilege'])?__('Only Admin'):NULL?></span>
                                                    <span class="label label-info "><?=(isset($field['show_listing']) AND $field['show_listing'])?__('Show listing'):NULL?></span>
                                                </div>
                                                <a 
                                                    href="<?=Route::url('oc-panel', array('controller'=>'category', 'action'=>'remove_customfield','id'=>$category->id_category))?>?cf=<?=$name?>" 
                                                    class="drag-action index-delete" 
                                                    title="<?=__('Are you sure you want to remove it?')?>" 
                                                    data-id="li_<?=$name?>" 
                                                    data-placement="left" 
                                                    data-href="<?=Route::url('oc-panel', array('controller'=>'category', 'action'=>'remove_customfield','id'=>$category->id_category))?>?cf=<?=$name?>" 
                                                    data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                                    data-btnCancelLabel="<?=__('No way!')?>">
                                                    <i class="glyphicon glyphicon-remove"></i>
                                                </a>
                                            </div>
                                        </li>
                                    <?endforeach?>
                            </ol><!--ol_1-->
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            <?endif?>
            <div class="panel panel-default">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?=__('New Custom Field')?></h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="<?=Route::url('oc-panel', array('controller'=>'category', 'action'=>'create_customfield', 'id'=>$category->id_category))?>">
                            <?=Form::errors()?>
                          
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label"><?=__('Name')?></label>
                                    <input class="form-control" type="text" name="name" placeholder="<?=__('Name')?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label"><?=__('Label')?></label>
                                    <input class="form-control" type="text" name="label" placeholder="<?=__('Label')?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label"><?=__('Tooltip')?></label>
                                    <input class="form-control" type="text" name="tooltip" placeholder="<?=__('Tooltip')?>" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label" for="type"><?=__('Type')?></label>
                                    <select name="type" class="form-control" id="cf_type_fileds" required>
                                        <option value="string"><?=__('Text 256 Chars')?></option>
                                        <option value="textarea"><?=__('Text Long')?></option>
                                        <option value="integer"><?=__('Number')?></option>  
                                        <option value="decimal"><?=__('Number Decimal')?></option>
                                        <option value="date"><?=__('Date')?></option>
                                        <option value="select"><?=__('Select')?></option>
                                        <option value="radio"><?=__('Radio')?></option>
                                        <option value="email"><?=__('Email')?></option>
                                        <option value="checkbox"><?=__('Checkbox')?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label"><?=__('Values')?></label>
                                    <input class="form-control" id="cf_values_input" type="text" name="values" placeholder="<?=__('Comma separated for select')?>">
                                </div>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="required"> 
                                    <?=__('Required')?>
                                </label>
                            </div>
                            <div class="help-block"><?=__('Required field to submit a new ad.')?></div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="searchable"> 
                                    <?=__('Searchable')?>
                                </label>
                                <div class="help-block"><?=__('Search in ads will include this field as well.')?></div>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="admin_privilege"> 
                                    <?=__('Admin Privileged')?>
                                </label>
                                <div class="help-block"><?=__('Can be seen and edited only by admin.')?></div>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="show_listing"> 
                                    <?=__('Show Listing')?>
                                </label>
                                <div class="help-block"><?=__('Can be seen in the list of ads while browsing.')?></div>
                            </div>
                          
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary"><?=__('Create')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?endif?>
    </div><!--end col-md-6-->
</div>