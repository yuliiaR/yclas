<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?= $post->title;?></h1>
</div>

<div class="well">
    <a class="label" href="<?=Route::url('profile',  array('seoname'=>$post->user->seoname))?>"><?=$post->user->name?></a>
    <div class="pull-right">
        <span class="label label-info"><?=Date::format($post->created, core::config('general.date_format'))?></span>
    </div>    
</div>

<br/>

<div>
    <?=Text::bb2html($post->description,TRUE)?>
    <div class="pull-right">
        <a class="btn btn-success" href="<?=Route::url('blog',  array('seotitle'=>$next->seotitle))?>"><i class="icon-step-backward"></i><?=$next->title?></a>
        <a class="btn btn-success" href="<?=Route::url('blog',  array('seotitle'=>$previous->seotitle))?>"><?=$previous->title?> <i class="icon-step-forward"></i></a>
    </div>  
</div>  
    
<?=$post->disqus()?>