<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>

<?if (!$install && !empty($error_msg)):?>
    <div class="alert alert-danger"><?=$error_msg?></div>
    <?install::view('hosting')?>
<?elseif($install==TRUE):?>
    <div class="alert alert-success"><?=__('Congratulations');?></div>
    <div class="jumbotron">
        <h1><?=__('Installation done');?></h1>
        <p>
            <?=__('Please now erase the folder');?> <code>/install/</code><br>
        
            <a class="btn btn-success btn-large" href="<?=install::request('SITE_URL')?>"><?=__('Go to Your Website')?></a>
            
            <a class="btn btn-warning btn-large" href="<?=install::request('SITE_URL')?>oc-panel/home/">Admin</a> 
            <?if(install::request('ADMIN_EMAIL'))?><span class="help-block">user: <?=install::request('ADMIN_EMAIL')?> pass: <?=install::request('ADMIN_PWD')?></span>
            <hr>
            <a class="btn btn-primary btn-large" href="http://j.mp/thanksdonate"><?=__('Make a donation')?></a>
            <?=__('We really appreciate it')?>.
        </p>
    </div>
<?endif?>