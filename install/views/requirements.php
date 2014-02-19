<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>

<h2><?=__("Requirements")?> OC v.<?=install::version?></h2>

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