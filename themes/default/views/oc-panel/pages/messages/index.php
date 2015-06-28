<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>
<div class="page-header">
    <h1><?=__('Inbox')?></h1>
</div>

<div class="panel">
    <?if (count($messages) > 0):?>
        <div class="btn-toolbar">
            <div class="btn-group">
                <a href="?status=" class="btn btn-cta <?=(!is_numeric(core::get('status')))?'btn-primary':'btn-default'?>">
                    <?=__('All')?>
                </a>
                <a href="?status=<?=Model_Message::STATUS_NOTREAD?>" class="btn btn-cta <?=(core::get('status',-1)==Model_Message::STATUS_NOTREAD)?'btn-primary':'btn-default'?>">
                    <?=__('Unread')?>
                </a>
            </div>
            <div class="btn-group">
                <a href="?status=<?=Model_Message::STATUS_SPAM?>" class="btn btn-cta <?=(core::get('status',-1)==Model_Message::STATUS_SPAM)?'btn-primary':'btn-default'?>">
                    <?=__('Spam')?>
                </a>
            </div>
        </div>
        <br>
        <table class="table table-striped">
            <thead>
                 <tr>
                    <th><?=__('Title')?> / <?=__('From')?></th>
                    <th><?=__('Date')?></th>
                    <th><?=__('Last Answer')?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($messages as $message):?>
                    <tr style="<?=($message->status == Model_Message::STATUS_NOTREAD AND $message->from->id_user != Auth::instance()->get_user()->id_user) ? 'font-weight: bold;' : NULL?>">
                        <td>
                            <p>
                                <?if(isset($message->ad->title)):?>
                                    <?=$message->ad->title?>
                                <?else:?>
                                    <?=__('Direct Message')?>
                                <?endif?>
                                <?if ($message->status == Model_Message::STATUS_NOTREAD AND $message->from->id_user != Auth::instance()->get_user()->id_user) :?>
                                    <span class="label label-warning"><?=__('Unread')?></span>
                                <?endif?>
                                <br>
                                <a href="<?=Route::url('profile',  array('seoname'=>$message->from->seoname))?>"><?=$message->from->name?></a>
                            </p>
                        </td>
                        <td><?=$message->parent->created?></td>
                        <td><?=(empty($message->parent->date_read))?__('None'):$message->created?></td>
                        <td class="text-right">
                            <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'message','id'=>($message->id_message_parent != NULL) ? $message->id_message_parent : $message->id_message))?>" 
                                class="btn btn-xs <?=($message->status == Model_Message::STATUS_NOTREAD AND $message->from->id_user != Auth::instance()->get_user()->id_user) ? 'btn-warning' : 'btn-default'?>"
                            >
                                <i class="fa fa-envelope"></i>
                            </a>
                        </td>
                    </tr>
                <?endforeach?>
            </tbody>
        </table>
    <?else:?>
        <h3><?=__('You don’t have any messages yet.')?></h3>
    <?endif?>
</div><!--//panel-->

<?if(isset($pagination)):?>
    <div class="text-center">
        <?=$pagination?>
    </div>
<?endif?>