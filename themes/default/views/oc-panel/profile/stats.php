<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('Statistics')?>
    <?if ($advert->loaded()):?>
    : <?=$advert->title?>
    <?endif?>
    </h1>   
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <table class="table table-bordered">
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
                        <td><b><?=__('Contacts')?></b></td>
                        <td><?=$contacts_today?></td>
                        <td><?=$contacts_yesterday?></td>
                        <td><?=$contacts_month?></td>
                        <td><?=$contacts_total?></td>
                    </tr>
                    <tr>
                        <td><b><?=__('Visits')?></b></td>
                        <td><?=$visits_today?></td>
                        <td><?=$visits_yesterday?></td>
                        <td><?=$visits_month?></td>
                        <td><?=$visits_total?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Charts')?></h3>
            </div>
            <div class="panel-body">
                <form id="edit-profile" class="form-inline text-center" method="post" action="">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?=__('From')?></div>
                            <input type="text" class="form-control" id="from_date" name="from_date" value="<?=$from_date?>" data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <span>-</span>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?=__('To')?></div>
                            <input type="text" class="form-control" id="to_date" name="to_date" value="<?=$to_date?>" data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><?=__('Filter')?></button>
                    <div>
                        <br>
                        <strong class="text-center"><?=__('Views and Contacts statistic')?></strong>
                        <?=Chart::line($stats_daily, array('height'  => 400,
                                                           'width'   => 400,
                                                           'options' => array('responsive' => true, 'maintainAspectRatio' => false, 'scaleShowVerticalLines' => false, 'multiTooltipTemplate' => '<%= datasetLabel %> - <%= value %>')))?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>