<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
	<h1><?=__('Newsletter')?></h1>
    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'settings','action'=>'email'))?>?force=1">
  <?=__('Email Settings')?></a>
<p><?=__('You can send a mass email to all your users that are active.')?> <span class="badge badge-info"><?=$count?></span></p>
    </div>

    <form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'newsletter','action'=>'index'))?>">         
          <?=Form::errors()?>        
    

        <div class="control-group">
        <label class="control-label"><?=__('From')?>:</label>
        <div class="controls">
        <input  type="text" name="from" value="<?=Auth::instance()->get_user()->name?>" class="span6"  />
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__('From Email')?>:</label>
        <div class="controls">
        <input  type="text" name="from_email" value="<?=Auth::instance()->get_user()->email?>" class="span6"  />
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__('Subject')?>:</label>
        <div class="controls">
        <input  type="text" name="subject" value="" class="span6"  />
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__('Message')?>:</label>
        <div class="controls">
        <textarea  name="description"  class="span6" rows="15" ></textarea>
        </div>
      </div>
          
          
      <div class="form-actions">
        <a href="<?=Route::url('oc-panel')?>" class="btn"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
      </div>
    </form>    