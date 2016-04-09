<?php defined('SYSPATH') or die('No direct script access.');?>

<ul class="nav nav-tabs nav-tabs-simple">
    <?=Theme::admin_link(__('Optimize'), 'tools','optimize','oc-panel')?>
    <?=Theme::admin_link(__('Sitemap'), 'tools','sitemap','oc-panel')?>
    <?=Theme::admin_link(__('Migration'), 'tools','migration','oc-panel')?>
    <?=Theme::admin_link(__('Cache'), 'tools','cache','oc-panel')?>
    <?=Theme::admin_link(__('Logs'), 'tools','logs','oc-panel')?>
    <?=Theme::admin_link(__('PHP Info'), 'tools','phpinfo','oc-panel')?>
</ul>

<div class="panel panel-default">
    <div class="panel-body">
        <h1 class="page-header page-title">
            <?=__('Migration')?> from OC 1.7.x/1.8.x to 2.x
            <a target="_blank" href="https://docs.yclas.com/how-to-upgrade-1-7-x1-8-x-to-2-x/">
                <i class="fa fa-question-circle"></i>
            </a>
        </h1>
        <hr>
        <p><?=__("Your PHP time limit is")?> <?=ini_get('max_execution_time')?> <?=__("seconds")?>
                <form method="post" action="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'migration'))?>">         
                    <?=Form::errors()?>        
                    <div class="form-group">
                        <label class="control-label"><?=__("Host name")?>:</label>
                        <input type="text" name="hostname" value="<?=$db_config['connection']['hostname']?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?=__("User name")?>:</label>
                        <input type="text" name="username"  value="<?=$db_config['connection']['username']?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?=__("Password")?>:</label>
                        <input type="text" name="password" value="" class="form-control" />   
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?=__("Database name")?>:</label>
                        <input type="text" name="database" value="<?=$db_config['connection']['database']?>"  class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?=__("Database charset")?>:</label>
                                <input type="text" name="charset" value="<?=$db_config['charset']?>"  class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?=__("Table prefix")?>:</label>
                                <input type="text" name="table_prefix" value="oc_" class="form-control" />
                    </div>
                    <hr>
                    <a href="<?=Route::url('oc-panel')?>" class="btn btn-default"><?=__('Cancel')?></a>
                     <button type="submit" class="btn btn-primary"><?=__('Migrate')?></button>
                </form>
    </div>
</div>