<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <?if ($page->loaded()):?>
        <h1><?=$page->title?></h1>
        <?=$page->description?>
    <?else:?>
        <h2><?=__('Thanks for submitting your advertisement')?></h2>
    <?endif?>
</div>

<?if(core::config('payment.to_featured') != FALSE AND $ad->featured < Date::unix2mysql()):?>
    <p>
        <a class="btn btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_featured','controller'=>'ad','id'=>$ad->id_ad))?>">
            <?=__('Go Featured!')?> <?=i18n::format_currency(Model_Order::get_featured_price(),core::config('payment.paypal_currency'))?>
        </a>
    </p>
<?endif?>