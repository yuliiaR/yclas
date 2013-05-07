
<div class="control-group">
    <?= FORM::label('locale', __('Language'), array('class'=>'control-label', 'for'=>'locale'))?>
    <div class="controls">
    <?= FORM::select("locale", i18n::get_languages(), core::config('i18n.locale'))?> 
    </div>
</div>

<form enctype="multipart/form-data" class="form form-horizontal" accept-charset="utf-8" method="post" action="<?=Request::current()->url()?>">
    <div class="form-actions">
        <input type="submit" class="btn btn-primary" value="Submit" name="translation[submit]">
        <a href="<?=Request::current()->url().URL::query(array('parse'=>1))?>" class="btn btn-warning">Regenerate translation base file</a>
    </div>

    <?$cont = 1;?>
    <?foreach($strings_en as $key => $value):?>
        <? $value = (isset($strings_default[$key])) ? $strings_default[$key] : ''?>

        <div class="control-group <?=($value)? '': 'error'?>">
            <label class="control-label" for="formorm_name">#<?=$cont?></label>
            <div class="controls">
                <textarea disabled = "disabled" name="labels[<?=$cont?>]"><?=$key?></textarea>
                <input type="hidden" maxlength="100" value="<?=$key?>" name="keys[<?=$cont?>]">
                <textarea name="translations[<?=$cont?>]"><?=$value?></textarea>
            </div>
        </div>
        <?$cont++;?>
    <?endforeach;?>

    <div class="form-actions">
        <input type="submit" class="btn btn-primary" value="Submit" name="translation[submit]">
    </div>
</form>