<?php defined('SYSPATH') or die('No direct script access.');?>

<?if (Core::get('print')!=1):?>
    <form class="form-inline pull-right" method="get" action="<?=URL::current();?>" style="margin-bottom:20px;">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><?=__('From')?></div>
                <input type="text" class="form-control input-sm" id="from_date" name="from_date" value="<?=core::request('from_date')?>" data-date="<?=core::request('from_date')?>" data-date-format="yyyy-mm-dd">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        <span>-</span>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><?=__('To')?></div>
                <input type="text" class="form-control input-sm" id="to_date" name="to_date" value="<?=core::request('to_date')?>" data-date="<?=core::request('to_date')?>" data-date-format="yyyy-mm-dd">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <select name="status" id="status" class="form-control disable-chosen" >
            <option value="none" <?=(core::request('status')==NULL OR core::request('status')=='none')?'SELECTED':''?>><?=__('Status')?></option>
            <?foreach (Model_Order::$statuses as $value=>$status):?>
                <option value="<?=$value?>" <?=(core::request('status')==$value AND core::request('status')!=NULL AND core::request('status')!='none')?'SELECTED':''?> ><?=$status?></option>
            <?endforeach?>
        </select>

        <div class="form-group">
            <select name="items_per_page" id="items_per_page" class="form-control" >
                <option value="10"><?=__('Items per page')?></option>
                <?foreach (range(10, 100,10) as $num):?>
                    <option value="<?=$num?>" <?=(core::request('items_per_page')==$num)?'SELECTED':''?> ><?=$num?></option>
                <?endforeach?>
            </select>
        </div>
        <div class="form-group">
            <input type="text" class="form-control input-sm search-query" name="email" placeholder="<?=__('email')?>" value="<?=core::request('email')?>">
        </div>
        <button type="submit" class="btn btn-primary"><?=__('Filter')?></button>
        <a class="btn btn-warning" href="<?=Route::url('oc-panel', array('controller'=>'order', 'action'=>'index'))?>">
            <?=__('Reset')?>
        </a>
    </form>
    
    <div class="page-header">    
        <h1><?=__('Orders')?></h1> 
        <p><?="<a target='_blank' href='http://docs.yclas.com/how-to-manage-orders/'>".__('Read more')."</a>"?></p>
    </div>
<?endif?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?=__('User') ?></th>
                        <th><?=__('Ad') ?></th>
                        <th><?=__('Product') ?></th>
                        <th><?=__('Amount') ?></th>
                        <th><?=__('Paymethod') ?></th>
                        <th><?=__('Paid') ?></th>
                        <?if (Core::get('print')!=1):?>
                        <th><?=__('Actions') ?></th>
                        <?endif?>
                    </tr>
                </thead>
                <tbody>
                    <?foreach($orders as $order):?>
                        <tr id="tr<?=$order->pk()?>">
                            <td><?=$order->pk()?></td>
                            <td><a href="<?=Route::url('oc-panel', array('controller'=> 'user', 'action'=>'update','id'=>$order->user->pk())) ?>">
                                <?=$order->user->name?></a> - <?=$order->user->email?>
                            </td>
                            <td><a href="<?=Route::url('oc-panel', array('controller'=> 'myads', 'action'=>'update','id'=>$order->ad->pk())) ?>" title="<?=HTML::chars($order->ad->title)?>">
                                <?=Text::limit_chars($order->ad->title, 30, NULL, TRUE)?></a></td>

                            <td><?=Model_Order::product_desc($order->id_product)?></td>
                            <td><?=i18n::format_currency($order->amount, $order->currency)?></td>

                            <td><?=$order->paymethod?></td>
                            <td>
                                <?if($order->pay_date==NULL):?>
                                    <a title="<?=__('Mark as paid')?>" class="btn btn-warning" href="<?=Route::url('oc-panel', array('controller'=> 'order', 'action'=>'pay','id'=>$order->id_order))?>">
                                    <i class="glyphicon glyphicon-usd"></i> <?=__('Mark as paid')?>
                                </a>
                                <?else:?>
                                    <?=$order->pay_date?>
                                <?endif?>
                            </td>

                            <?if (Core::get('print')!=1):?>
                            <td width="80" style="width:80px;">
                                <?if ($controller->allowed_crud_action('update')):?>
                                <a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$order->pk()))?>">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <?endif?>
                            </td>
                            <?endif?>
        
                        </tr>
                    <?endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="text-center"><?=$pagination?></div>

<?if( ! core::get('print')):?>
    <div class="pull-right">
        <a target="_blank" class="btn btn-xs btn-success" title="<?=__('Print this')?>" href="<?=Route::url('oc-panel', array('controller'=>'order', 'action'=>'index')).URL::query(array('print'=>1))?>"><i class="glyphicon glyphicon-print"></i><?=__('Print this')?></a>
    </div>
<?endif;?>