<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('Migration')?> from OC 1.7.x/1.8.x to 2.x</h1>
    <p><?=__("Your PHP time limit is")?> <?=ini_get('max_execution_time')?> <?=__("seconds")?>
        <a href="https://docs.yclas.com/how-to-upgrade-1-7-x1-8-x-to-2-x/" target="_blank">How to migrate OC 1.7.x/1.8.x to 2.x</a>
    </p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-horizontal">
                    <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'migration'))?>">         
                        <?=Form::errors()?>        
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__("Host name")?>:</label>
                            <div class="col-sm-8">
                                <input type="text" name="hostname" value="<?=$db_config['connection']['hostname']?>" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__("User name")?>:</label>
                            <div class="col-sm-8">
                                <input type="text" name="username"  value="<?=$db_config['connection']['username']?>" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__("Password")?>:</label>
                            <div class="col-sm-8">
                                <input type="text" name="password" value="" class="form-control" />   
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__("Database name")?>:</label>
                            <div class="col-sm-8">
                                <input type="text" name="database" value="<?=$db_config['connection']['database']?>"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__("Database charset")?>:</label>
                            <div class="col-sm-8">
                                <input type="text" name="charset" value="<?=$db_config['charset']?>"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4"><?=__("Table prefix")?>:</label>
                            <div class="col-sm-8">
                                <input type="text" name="table_prefix" value="oc_" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-4">
                                <a href="<?=Route::url('oc-panel')?>" class="btn btn-default"><?=__('Cancel')?></a>
                                <button type="submit" class="btn btn-primary"><?=__('Migrate')?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>