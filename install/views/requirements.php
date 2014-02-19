<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>

<div class="page-header">
    <h1><?=__("Software Requirements")?>  v.<?=install::version?></h1>
    <p><?=__('In this page you can see the requirements checks we do before we install.')?></p>
</div>

<div class="well">   
    <ul class="">
        <?foreach (install::requirements() as $name => $values):
            $color = ($values['result'])?'success':'important';?>
            <li><i class="glyphicon glyphicon-<?=($values['result'])?"ok":"remove"?>"></i> 
                <?printf ('<span class="label label-%s">%s</span>',$color,$name);?>    
            </li>
        <?endforeach?>
    </ul>
</div>