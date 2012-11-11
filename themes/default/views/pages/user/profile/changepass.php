<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<?=View::factory('sidebar')?>
	<div class="span10">
	<?=Breadcrumbs::render('breadcrumbs')?> 
	<div class="page-header">
		<h1><?=__('Change password')?></h1>
	</div>
	<?=$msg?>
	<form class="well form-horizontal"  method="post" action="<?=Route::url('user',array('directory'=>'user','controller'=>'profile','action'=>'changepass'))?>">         
          <?=Form::errors()?>        
          
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
          	<a href="<?=Route::url('user')?>" class="btn"><?=__('Cancel')?></a>
            <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
          </div>
          <?=Form::CSRF()?>
	</form>    

	</div><!--/span--> 
</div><!--/row-->  	