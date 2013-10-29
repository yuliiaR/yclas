<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Emails')?></h1>
    
    <a class="btn btn-primary pull-right" href="http://open-classifieds.com/documentation/translate/"><?=__('New email')?></a>

</div>
 <?= FORM::open(Route::url('post_new',array('controller'=>'new','action'=>'index')), array('class'=>'form-horizontal post_new', 'enctype'=>'multipart/form-data'))?>
<fieldset>
    <div class="control-group">
        <?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
        <div class="controls">
            <?= FORM::input('title', $cont->title, array('placeholder' => __('title'), 'class' => '', 'id' => 'title', 'required'))?>
        </div>
    </div>
    <div class="control-group">
        <?= FORM::label('locale', __('Locale'), array('class'=>'control-label', 'for'=>'locale'))?>
        <div class="controls">
            <?= FORM::input('locale', $cont->locale, array('placeholder' => __('locale'), 'class' => '', 'id' => 'locale', 'required'))?>
        </div>
    </div>
    <div class="control-group">
        <?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description'))?>
        <div class="controls">
            <?= FORM::textarea('description', $cont->description, array('placeholder' => __('description'), 'class' => '', 'id' => 'description', 'required'))?>
        </div>
    </div>
</fieldset>
<?= FORM::close()?>
   

