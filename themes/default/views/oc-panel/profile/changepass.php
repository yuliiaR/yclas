<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">

    <div class="span10">
    	<div class="page-header">
    		<h1><?=__('Change password')?></h1>
    	</div>
    	
    	<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'changepass'))?>">         
              <?=Form::errors()?> 

              <div class="control-group">
                <label class="control-label"><?=__('Old password')?></label>
                <div class="controls docs-input-sizes">
                <input class="input-medium" type="password" name="password0" placeholder="<?=__('Password')?>">
                </div>
              </div>       
              
              <div class="control-group">
                <label class="control-label"><?=__('New password')?></label>
                <div class="controls docs-input-sizes">
                <input class="input-medium" type="password" name="password1" placeholder="<?=__('Password')?>">
                </div>
              </div>
              
              <div class="control-group">
                <label class="control-label"><?=__('Repat password')?></label>
                <div class="controls docs-input-sizes">
                <input class="input-medium" type="password" name="password2" placeholder="<?=__('Password')?>">
                  <p class="help-block">
                  		<?=__('Type your password twice to change')?>
                  </p>
                </div>
              </div>
              
              <div class="form-actions">
              	<a href="<?=Route::url('oc-panel')?>" class="btn"><?=__('Cancel')?></a>
                <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
              </div>
              <?=Form::CSRF()?>
    	</form>
    </div><!--end span10-->
</div> <!--end row-->   