<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>


<div class="well sidebar-nav">   
    <ul class="nav nav-list">
        <li class="nav-header"><?=__("Requirements")?> OC v.<?=install::version?></li>
        <li class="divider"></li>
        
        <?foreach (install::$checks as $name => $values):
            if ($values['mandatory'] == TRUE AND $values['result'] == FALSE)
                $succeed = FALSE;

            if ($values['result'] == FALSE)
                $msg .= $values['message'].'<br>';

            $color = ($values['result'])?'success':'important';
        ?>

            <li><i class="glyphicon glyphicon-<?=($values['result'])?"ok":"remove"?>"></i> 
                <?printf ('<span class="label label-%s">%s</span>',$color,$name);?>    
            </li>
        <?endforeach?>
        
    </ul>
    
   </div>
<!--/.well -->


<?=str_replace('<table', '<table class="table table-striped table-bordered"', install::phpinfo())?>