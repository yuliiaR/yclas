<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('Welcome')?> <?=Auth::instance()->get_user()->name?></h1>
    <p><?=__('Thanks for using Open Classifieds.')?> 
        <?=__('Your installation version is')?> <span class="label label-info"><?=core::version?></span> 
        <a class="btn btn-xs btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'index'))?>?reload=1">
                        <?=__('Check for updates')?></a>
    </p>
    
    <div class="clearfix"></div>
    <p><?=__('You need help or you have some questions')?>
        <?if(Theme::get('premium')!=1):?>
            <a class="btn btn-info btn-xs" target="_blank" href="http://forums.open-classifieds.com/"><i class="glyphicon glyphicon-wrench"></i> <?=__('Forum')?></a>
        <?else:?>
            <a class="btn btn-info btn-xs" target="_blank" href="http://market.open-classifieds.com/"><i class="glyphicon glyphicon-wrench"></i> <?=__('Support')?></a>
        <?endif?>
        <a class="btn btn-info btn-xs" target="_blank" href="http://open-classifieds.com/support/"><i class="glyphicon glyphicon-question-sign"></i> <?=__('FAQ')?></a>
        <a class="btn btn-info btn-xs" target="_blank" href="http://open-classifieds.com/blog/"><i class="glyphicon glyphicon-pencil"></i> <?=__('Blog')?></a>
    </p>
</div>

    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-info">
        <div class="panel-heading"><h3><?=__('Latest News')?><!-- Place this tag where you want the share button to render. -->
    <div class="g-plus" data-action="share"></div>

    <!-- Place this tag after the last share tag. -->
    <script type="text/javascript">
      (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/platform.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
      })();
    </script></h3></div>
            <div class="panel-body">
                <ul>
                    <?foreach ($rss as $item):?>
                        <li><a target="_blank" href="<?=$item['link']?>" title="<?=$item['title']?>"><?=$item['title']?></a></li>
                        <div class="divider"></div>
                    <?endforeach?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <a class="twitter-timeline" href="https://twitter.com/openclassifieds" data-widget-id="328935540424572929">Tweets by @openclassifieds</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fopenclassifieds&amp;width=250&amp;height=290&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=true&amp;appId=181472118540903" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:290px;" allowTransparency="true"></iframe>
    </div>


    

