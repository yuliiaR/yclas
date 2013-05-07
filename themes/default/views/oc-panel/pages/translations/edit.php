<div class="page-header">
    <h1><?=__('Translations')?> <?=$edit_language?></h1>
    <p><?=__('Here you can modify any text you find in your web')?></p>
</div>

<form enctype="multipart/form-data" class="form form-horizontal" accept-charset="utf-8" method="post" action="<?=Request::current()->url()?>">

    <table class="table table-bordered">
    <tr>
        <th>#</th>
        <th><?=__('Original Translation')?></th>
        <th><button class="btn" id="button-copy-all" ><i class="icon-arrow-right"></i></button></th>
        <th><?=__('Translation')?> <?=$edit_language?></th>
        <th></th>
    </tr>
    <button type="submit" class="btn btn-primary pull-right" name="translation[submit]"><i class="icon-hdd icon-white"></i> <?=__('Save')?></button>

    <?$cont = 1;?>
    <?foreach($strings_en as $key => $value):?>
        <? $value = (isset($strings_default[$key])) ? $strings_default[$key] : ''?>
        <tr class="<?=($value)? 'success': 'error'?>">
            <td width="5%"><?=$cont?></td>
            <td>
                <textarea id="orig_<?=$cont?>" disabled style="width: 100%" name="labels[<?=$cont?>]"><?=$key?></textarea>
            </td>
            <td width="5%">
                <button class="btn button-copy" data-orig="orig_<?=$cont?>" data-dest="dest_<?=$cont?>" ><i class="icon-arrow-right"></i></button>
                <br>
                <a target="_blank" class="btn button-translate" 
                    href="http://translate.google.com/#en/<?=i18n::html_lang()?>/<?=urlencode($key)?>">
                    <i class="icon-globe"></i></a>
            </td>
            <td>  
                <textarea id="dest_<?=$cont?>" style="width: 100%" name="translations[<?=$cont?>]"><?=$value?></textarea>
            </td>
            <td width="5%">
                <button type="submit" class="btn btn-primary" name="translation[submit]"><i class="icon-hdd icon-white"></i></button>
            </td>
            <input type="hidden" value="<?=$key?>" name="keys[<?=$cont?>]">
        </tr>
        <?$cont++;?>
    <?endforeach;?>

    </table>
    <button type="submit" class="btn btn-primary pull-right" name="translation[submit]"><i class="icon-hdd icon-white"></i> <?=__('Save')?></button>


    <div id="translate-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-body">
        </div>
    </div>

</form>