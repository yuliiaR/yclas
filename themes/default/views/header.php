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
			
			<?$cat = new Model_Category(); $cat_list = $cat->get_categories();?>
			
			<div class="nav-collapse">
				<ul class="nav">

					<li><a href="/">Cat 2</a></li>
					<li class="active"><a href="<?= Route::url('default', array('controller'=>'ad', 'action'=>'all'));?>">Listing</a></li>
					<li><a href="<?= Route::url('contact');?>">Contact Us</a></li>
					<li class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories <b class="caret"></b></a>
		              <ul class="dropdown-menu">

		              	<?foreach($cat_list as $cat_list => $value):?>

		              		<?if($value['id'] <= $value['parent']):?>
								<li class="nav-header"><?=$cat_list?></li>
								<li class="divider"></li>							
							<?endif?>
								<li><a title="<?=$cat_list?>" href="<?=Route::url('sort_by', array('category'=>$cat_list))?>"> <?=$cat_list?></a></li>
						<?endforeach?>
		              </ul>
		            </li>
		            <li><a href="/">Cat 5</a></li>
		        </ul>
		        <?= FORM::open(Route::url('default',array('controller'=>'ad','action'=>'all')), array('class'=>'navbar-search pull-left', 'method'=>'GET', 'action'=>'','enctype'=>'multipart/form-data'))?>
		            <input type="text" name="search" class="search-query span2" placeholder="<?=__('Search')?>">
		        <?= FORM::close()?>
		        
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
				<?=View::factory('pages/auth/login-form')?>
    		</div>
    </div>
    
    <div id="forgot-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Forgot password')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/auth/forgot-form')?>
    		</div>
    </div>
    
     <div id="register-modal" class="modal hide fade">
            <div class="modal-header">
              <a class="close" data-dismiss="modal" >&times;</a>
              <h3><?=__('Register')?></h3>
            </div>
            
            <div class="modal-body">
				<?=View::factory('pages/auth/register-form')?>
    		</div>
    </div>
<?endif?>