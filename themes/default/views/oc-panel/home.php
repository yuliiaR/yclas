<?php defined('SYSPATH') or die('No direct script access.');?>

<div id="page-welcome" class="page-header">
    <h1><?=__('Welcome')?> <?=Auth::instance()->get_user()->name?></h1>
</div>

<p>
    <?=__('Thanks for using Open Classifieds.')?> 
    <?=__('Your installation version is')?> <span class="label label-info"><?=core::VERSION?></span> 
    <a class="btn btn-xs btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'index'))?>?reload=1" title="<?=__('Check for updates')?>">
        <?=__('Check for updates')?>                
    </a>
</p>

<p>
    <?=__('You need help or you have some questions')?>
    <?if(Theme::get('premium')!=1):?>
        <a class="btn btn-info btn-xs" target="_blank" href="http://forums.open-classifieds.com/"><i class="glyphicon glyphicon-wrench"></i> <?=__('Forum')?></a>
    <?else:?>
        <a class="btn btn-info btn-xs" target="_blank" href="http://market.open-classifieds.com/oc-panel/support/index"><i class="glyphicon glyphicon-wrench"></i> <?=__('Support')?></a>
    <?endif?>
    <a class="btn btn-info btn-xs" target="_blank" href="http://open-classifieds.com/support/"><i class="glyphicon glyphicon-question-sign"></i> <?=__('FAQ')?></a>
    <a class="btn btn-info btn-xs" target="_blank" href="http://open-classifieds.com/blog/"><i class="glyphicon glyphicon-pencil"></i> <?=__('Blog')?></a>
</p>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Site Statistics')?></h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?=__('Today')?></th>
                            <th><?=__('Yesterday')?></th>
                            <th><?=__('Last 30 days')?></th>
                            <th><?=__('Total')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b><?=__('Ads')?></b></td>
                            <td><?=$ads_today?></td>
                            <td><?=$ads_yesterday?></td>
                            <td><?=$ads_month?></td>
                            <td><?=$ads_total?></td>
                        </tr>
                        <tr>
                            <td><b><?=__('Visits')?></b></td>
                            <td><?=$visits_today?></td>
                            <td><?=$visits_yesterday?></td>
                            <td><?=$visits_month?></td>
                            <td><?=$visits_total?></td>
                        </tr>
                        <tr>
                            <td><b><?=__('Sales')?></b></td>
                            <td><?=$orders_today?></td>
                            <td><?=$orders_yesterday?></td>
                            <td><?=$orders_month?></td>
                            <td><?=$orders_total?></td>
                        </tr>
                    </tbody>
                </table>
                
                <ul class="nav nav-tabs" id="statsTabs">
                    <li class="active"><a href="#views" data-toggle="tab"><?=__('Views and Ads')?></a></li>
                    <li><a href="#sales" data-toggle="tab"><?=__('Sales')?></a></li>
                </ul>
                
                <div class="tab-content" >
                    <!-- VIEWS TAB -->
                    <div class="tab-pane fade in active" id="views">
                        <h6 class="text-center"><?=__('Views and Ads statistics')?></h6>
                        <div>
                            <?=Chart::line($stats_daily, array('height'  => 300,
                                                               'width'   => 400,
                                                               'options' => array('responsive' => true, 'maintainAspectRatio' => false, 'scaleShowVerticalLines' => false, 'multiTooltipTemplate' => '<%= datasetLabel %> - <%= value %>')))?>
                        </div>
                    </div>
                    <!-- SALES TAB -->
                    <div class="tab-pane fade in" id="sales">
                        <h6 class="text-center"><?=__('Sales statistics')?></h6>
                        <div>
                            <?=Chart::line($stats_orders, array('height'  => 300,
                                                                'width'   => 400,
                                                                'options' => array('responsive' => true, 'maintainAspectRatio' => false, 'scaleShowVerticalLines' => false, 'multiTooltipTemplate' => '<%= datasetLabel %> - <%= value %>')))?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.col-md-6 -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Latest Published Ads')?></h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?=__('Name')?></th>
                            <th><?=__('Category')?></th>
                            <th><?=__('Location')?></th>
                            <?if(core::config('advertisement.count_visits')==1):?>
                            <th><?=__('Hits')?></th>
                            <?endif?>
                            <th><?=__('Date')?></th>
                        </tr>
                    </thead>
                    <?if(isset($res)):?>
                        <tbody>
                            <? $i = 0; foreach($res as $ad):?>
                                    <tr>
                                        <td><?=$ad->id_ad?>
                
                                        <td><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><?= wordwrap($ad->title, 15, "<br />\n"); ?></a>
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
                
                                        <td><?= Date::format($ad->published, core::config('general.date_format'))?></td>
                                    </tr>
                            <?endforeach?>
                        </tbody>
                    <?endif?>
                </table>
            </div>
        </div>
    </div><!-- /.col-md-6 -->
</div><!-- /.row -->

<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Open-Classifieds <?=__('Latest News')?></h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?foreach ($rss as $item):?>
                        <a class="list-group-item" target="_blank" href="<?=$item['link']?>" title="<?=HTML::chars($item['title'])?>"><?=$item['title']?></a>
                    <?endforeach?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Tweets by @openclassifieds</h3>
            </div>
            <div class="panel-body">
                <a class="twitter-timeline" href="https://twitter.com/openclassifieds" data-widget-id="428842439499997185"></a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Open-Classifieds on Facebook</h3>
            </div>
            <div class="panel-body">
                <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fopenclassifieds&amp;width=350&amp;height=600&amp;colorscheme=dark&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true&amp;appId=181472118540903" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:600px;" allowTransparency="true"></iframe>
            </div>
        </div>
    </div>
</div>
