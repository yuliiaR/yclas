<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($ad->status != Model_Ad::STATUS_PUBLISHED && $permission === FALSE && ($ad->id_user != $user)):?>

<div class="page-header">
	<h1><?= __('This advertisement doesn´t exist, or is not yet published!')?></h1>
</div>

<?else:?>
    <?=Form::errors()?>

    <div class="well well-sm">
        <div class="row">
            <div class="col-xs-12 col-md-12 section-box">
                <h1>
                    <?=$ad->title.' '.__("Reviews")?>
                </h1>
                <hr />
                <div class="row rating-desc">
                    <div class="col-md-12">
                        <?for ($i=0; $i < round($ad->rate,1); $i++):?>
                            <span class="glyphicon glyphicon-star"></span>
                        <?endfor?>(<?=round($ad->rate,1)?>/<?=Model_Review::RATE_MAX?>)<span class="separator">|</span>
                        <span class="glyphicon glyphicon-comment"></span><?=count($reviews)?> <?=__('reviews')?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <hgroup class="mb20"></hgroup>
    <?if(count($reviews)):?>
        <?foreach ($reviews as $review):?>
        
        <article class="search-result row">
            <div class="col-xs-12 col-sm-12 col-md-3">
                <a title="<?=$review->user->name?>" class="thumbnail"><img src="<?=$review->user->get_profile_image()?>" alt="<?=__('Profile image')?>" height="140px"></a>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-2">
                <ul class="meta-search">
                    <li><i class="glyphicon glyphicon-calendar"></i> <span><?=$review->created?></span></li>
                    <li><i class="glyphicon glyphicon-time"></i> <span><?=Date::fuzzy_span(Date::mysql2unix($review->created))?></span></li>
                    <li><i class="glyphicon glyphicon-user"></i> <span><?=$review->user->name?></span></li>
                <?if ($review->rate!==NULL):?>
            
                <div class="rating">
                    <h1 class="rating-num"><?=round($review->rate,2)?>.0</h1>
                    <?for ($i=0; $i < round($review->rate,1); $i++):?>
                        <span class="glyphicon glyphicon-star"></span>
                    <?endfor?>
                </div>
                <?endif?>
                </ul>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7">
                <p><?=Text::bb2html($review->description,TRUE)?></p>                        
                <!-- <span class="plus"><a href="#" title="Lorem ipsum"><i class="glyphicon glyphicon-plus"></i></a></span> -->
            </div>
            <span class="clearfix borda"></span>
        </article>
        <hgroup class="mb20 mt20"></hgroup>
        <?endforeach?>

    <?elseif (count($reviews) == 0):?>
    <div class="page-header">
        <h3><?=__('We do not have any reviews for this product')?></h3>
    </div>
    <?endif?>


<?endif?>

