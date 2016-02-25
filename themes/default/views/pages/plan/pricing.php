<?php defined('SYSPATH') or die('No direct script access.');?>

<?if($subscription!==FALSE):?>
    <p><?=sprintf(__('You are subscribed to the plan %s until %s with %u ads left'),$subscription->plan->name,$subscription->expire_date,$subscription->amount_ads_left)?></p>
<?endif?>
<div class="row pricing">
    <?foreach ($plans as $plan):?>
        <?
            $current_plan = FALSE;
            if ($subscription!==FALSE AND $subscription->plan->id_plan==$plan->id_plan)
                $current_plan = TRUE;
        ?>
        <div class="col-md-4">
            <div class="well">
                <h3><b><?=$plan->name?></b></h3>
                <hr>
                <p><?=$plan->description?></p>
                <hr>
                <p>
                    <?if ($plan->days == 0 AND $plan->price>0):?>
                        <?=__('Pay once')?>
                    <?elseif ($plan->days == 365):?>
                        <?=__('Yearly')?>
                    <?elseif ($plan->days == 180):?>
                        <?=__('6 months')?>
                    <?elseif ($plan->days == 90):?>
                        <?=__('Quarterly')?>
                    <?elseif ($plan->days == 30):?>
                        <?=__('Monthly')?>
                    <?else:?>
                        <?=$plan->days?> <?=__('days')?>
                    <?endif?>
                </p>
                <hr>
                <p><?=$plan->amount_ads?> <?=_('Ads')?></p>
                <?if(Core::config('payment.stripe_connect')):?>
                <hr>
                <p><b><?=round($plan->marketplace_fee,1)?>% <?=__('market place fee')?></b></p>
                <?endif?>

                <hr>
                <a href="<?=Route::url('default', array('controller'=>'plan','action'=>'buy','id'=>$plan->seoname))?>" 
                    class="btn btn-<?=($current_plan)?'primary':'success'?> btn-block">
                    <?if($current_plan==TRUE):?>
                        <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> <?=__('Renew')?>
                    <?else:?>
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>     <?=__('Sign Up')?>
                    <?endif?>
                    <b><?=i18n::format_currency($plan->price,core::config('payment.paypal_currency'))?></b>
                </a>
                
            </div>
        </div>
    <?endforeach?>
</div>    
