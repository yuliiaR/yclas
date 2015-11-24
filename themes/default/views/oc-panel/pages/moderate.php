<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>
<div class="page-header">
    <h1><?=__('Moderation')?></h1>
</div>
<? $current_url = Model_Ad::STATUS_NOPUBLISHED?>
<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-bordered">
            <tr>
                <th>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="select-all" onclick="check_all();">
                        </label>
                    </div>
                </th>
                <th><?=__('Activate')?></th>
                <th><?=__('Name')?></th>
                <th><?=__('Category')?></th>
                <th><?=__('Location')?></th>
                <?if(core::config('advertisement.count_visits')==1):?>
                <th><?=__('Hits')?></th>
                <?endif?>
                <th><?=__('Status')?></th>
                <th><?=__('Date')?></th>
                <!-- in case there are no ads we dont show buttons -->
                <?if(isset($ads)):?>
                <th>
                    <a class="activate btn btn-success" 
                        href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate'))?>" 
                        onclick="return confirm('<?=__('Activate?')?>');"
                        rel="tooltip" title="<?=__('Activate')?>">
                        <i class="glyphicon   glyphicon-ok-sign"></i>
                    </a>
                    <a class="spam btn btn-warning" 
                            href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam'))?>" 
                            onclick="return confirm('<?=__('Spam?')?>');"
                            rel="tooltip" title="<?=__('Spam')?>">
                            <i class="glyphicon   glyphicon-fire"></i>
                        </a>
                    <a class="delete btn btn-danger " 
                        href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete'))?>"
                        onclick="return confirm('<?=__('Delete?')?>');"
                        rel="tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
                        <i class="glyphicon   glyphicon-remove"></i>
                    </a>
                </th>
                <?endif?>
            </tr>
            <?if(isset($ads)):?>
            <tbody>
            <?foreach($ads as $ad):?>  
                <tr id="tr<?=$ad->id_ad?>">
                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="<?= $ad->id_ad.'_'?>" class="checkbox">
                            </label>
                        </div>
                    </td>
                    <td>
                        <a
                            href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
                            class="btn btn-success index-moderation" 
                            title="<?=__('Activate?')?>" 
                            data-id="tr<?=$ad->id_ad?>" 
                            data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                            data-btnCancelLabel="<?=__('No way!')?>">
                            <i class="glyphicon glyphicon-ok-sign"></i> <?=$ad->id_ad?>
                        </a>
                    </td>
                    
                    <td><a href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"><?= wordwrap($ad->title, 45, "<br />\n"); ?></a>
                    </td>
        
                    <td><?= wordwrap($ad->category->name, 15, "<br />\n"); ?>
                         
                    <td>
                        <?if($ad->location->loaded()):?>
                            <?=wordwrap($ad->location->name, 15, "<br />\n");?>
                        <?else:?>
                            n/a
                        <?endif?>
                    </td>
                    <?if(core::config('advertisement.count_visits')==1):?>
                    <td><?=$ad->visits->count_all();?></td>
                    <?endif?>
        
                    <td>
                    <?if($ad->status == Model_Ad::STATUS_NOPUBLISHED):?>
                        <?=__('Not published')?>
                    <? elseif($ad->status == Model_Ad::STATUS_PUBLISHED):?>
                        <?=__('Published')?>
                    <? elseif($ad->status == Model_Ad::STATUS_SPAM):?>
                        <?=__('Spam')?>
                    <? elseif($ad->status == Model_Ad::STATUS_UNAVAILABLE):?>
                        <?=__('Unavailable')?>
                    <?endif?>
        
                    <?if( ($order = $ad->get_order())!==FALSE ):?>
                        <a class="label <?=($order->status==Model_Order::STATUS_PAID)?'label-success':'label-warning'?> " 
                            href="<?=Route::url('oc-panel', array('controller'=> 'order','action'=>'index'))?>?email=<?=$order->user->email?>">
                        <?if ($order->status==Model_Order::STATUS_CREATED):?>
                            <?=__('Not paid')?>
                        <?elseif ($order->status==Model_Order::STATUS_PAID):?>
                            <?=__('Paid')?>
                        <?endif?>
                            <?=i18n::format_currency($order->amount,$order->currency)?>
                        </a>
                    <?endif?>
                    </td>
        
                    <td><?= Date::format($ad->created, core::config('general.date_format'))?></td>
                     <td style="width:190px">
                        
                        <a class="btn btn-primary" 
                            href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>" 
                            rel="tooltip" title="<?=__('Edit')?>">
                            <i class="glyphicon   glyphicon-edit"></i>
                        </a>

                        <a
                            href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
                            class="btn btn-warning index-moderation" 
                            title="<?=__('Spam?')?>" 
                            data-id="tr<?=$ad->id_ad?>" 
                            data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                            data-btnCancelLabel="<?=__('No way!')?>">
                            <i class="glyphicon glyphicon-fire"></i>
                        </a>
        
                        <a
                            href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
                            class="btn btn-danger index-moderation" 
                            title="<?=__('Delete?')?>" 
                            data-id="tr<?=$ad->id_ad?>" 
                            data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                            data-btnCancelLabel="<?=__('No way!')?>">
                            <i class="glyphicon glyphicon-remove"></i>
                        </a>
                    </td>
                </tr>
            <?endforeach?>  
            </tbody>
            <?endif?>
        </table>
    </div>
</div>
<?if(isset($pagination)):?>
    <div class="text-center">
        <?=$pagination?>
    </div>
<?endif?>