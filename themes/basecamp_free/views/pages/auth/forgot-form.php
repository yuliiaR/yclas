<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="auth"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'forgot'))?>">         
	<div class="modal-body">         
		<?=Form::errors()?>
		<div class="form-group clearfix">
			<label class="col-xs-12 control-label"><?=_e('Email')?></label>
			<div class="col-xs-12">
				<input class="form-control" type="text" name="email" placeholder="<?=__('Email')?>">
			</div>
		</div>
	</div>
	<div class="modal-foot-controls clearfix">
		<a class="btn btn-base-dark pull-left reg-btn" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
			<?=_e('Register')?>
		</a>
		<button type="submit" class="btn btn-base-dark pull-right log-btn"><?=_e('Send')?></button>
	</div>
	<?=Form::CSRF('forgot')?>
</form>