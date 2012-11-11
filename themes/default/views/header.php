<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?=Route::url('default')?>">Site name</a>
			
			<div class="nav-collapse">
				<ul class="nav">
					<li><a href="/">Cat 2</a></li>
					<li class="active"><a href="/">Cat 1</a></li>
					<li><a href="/">Cat 2</a></li>
					<li class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
		              <ul class="dropdown-menu">
		                <li><a href="#">Action</a></li>
		                <li><a href="#">Another action</a></li>
		                <li><a href="#">Something else here</a></li>
		                <li class="divider"></li>
		                <li class="nav-header">Nav header</li>
		                <li><a href="#">Separated link</a></li>
		                <li><a href="#">One more separated link</a></li>
		              </ul>
		            </li>
		            <li><a href="/">Cat 5</a></li>
		        </ul>
		        
		        <form class="navbar-search pull-left" action="@todo">
		            <input type="text" name="q" class="search-query span2" placeholder="<?=__('Search')?>">
		        </form>
		        
				<div class="btn-group pull-right">
					<?=View::factory('widget_login')?>
				
					<a class="btn btn-primary" href="<?=Route::url('post_new')?>">
						<i class="icon-pencil icon-white"></i>
						<?=__('Publish new ')?>
					</a>				

				</div>
				
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>

<?if (!Auth::instance()->logged_in()):?>
	<div id="login-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Login')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/user/auth/login-form')?>
    		</div>
    </div>
    
    <div id="forgot-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Forgot password')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/user/auth/forgot-form')?>
    		</div>
    </div>
    
     <div id="register-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Register')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/user/auth/register-form')?>
    		</div>
    </div>
<?endif?>