<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1><?=__('Welcome')?></h1>
    <p><?=__('Thanks for using Open Classifieds.')?> 
        <?=__('Your installation version is')?> <span class="label label-info"><?=core::version?></span> 
        <a class="btn btn-mini btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'updates'))?>?reload=1">
                        <?=__('Check for updates')?></a>

    </p>
</div>



RSS blog read
Twitter
Follow us
Advertisement promote your site at Adserum $1 = 1000 impressions



<?if (core::config('theme.premium')!=1):?>
----------
Advertisement iframe offers
<script type="text/javascript">
if (typeof geoip_city!="function")document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://j.maxmind.com/app/geoip.js\"></scr"+"ipt>");
document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://api.adserum.com/sync.js?a=6&f=8&w=728&h=90\"></scr"+"ipt>");
</script>
<?endif?>