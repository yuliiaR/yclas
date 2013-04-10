<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?=Route::url('default')?>"><?=core::config('general.site_name')?></a>
			
			<?$cat = new Model_Category(); $cat_list = $cat->find_all(); $children_categ = $cat->get_category_children()?>
			
			<div class="nav-collapse main_nav">
				<ul class="nav">
					<?nav_link(__('Home'),'home', 'icon-home')?>
					<?nav_link(__('Listing'),'ad', 'icon-list' ,'all')?>
					<?nav_link(__('Contact Us'),'contact', 'icon-envelope', 'index', 'contact')?>
					<li class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories <b class="caret"></b></a>
		              <ul class="dropdown-menu">

		              	<?foreach($cat_list as $c ):?>
		              		<?if($c->id_category == $c->id_category_parent || $c->id_category_parent == 0 || $c->id_category_parent == NULL):?>

								<li class="nav-header"><p><a title="<?=$c->name?>" href="<?=Route::url('sort_by', array('category'=>$c->name))?>"><?=$c->name?></a></p></li>
															
							 	<?foreach($children_categ as $chi):?>
                            	<?if($chi['parent'] == $c->id_category):?>
                           			<li><a title="<?=$chi['name']?>" href="<?=Route::url('sort_by', array('category'=>$chi['name']))?>"><?=$chi['name']?> <span class="count_ads"><span class="badge badge-success"><?=$chi['count']?></span></span></a></li>
                           		<?endif?>
                         		<?endforeach?>
								<li class="divider"></li>
							<?endif?>
						<?endforeach?>
		              </ul>
		            </li>
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