<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($page->loaded()):?>
<h2><?=$page->title?></h2>
<?=$page->description?>
<?else:?>
<h2><?=__('Thanks')?></h2>
<?endif?>


<?if(core::config('payment.to_featured') != FALSE AND $ad->featured < Date::unix2mysql()):?>
    <a class="btn btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_featured','controller'=>'ad','id'=>$ad->id_ad))?>">
        <?=__('Go Featured!')?> <?=i18n::format_currency(Model_Order::get_featured_price(),core::config('payment.paypal_currency'))?>
    </a>
<?endif?>