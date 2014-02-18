<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>

<div class="page-header">
    <h1><?=__("Welcome to")?> <?=install::name?> <?=__("installation")?></h1>
    <p>
        <?=__("Welcome to the super easy and fast installation")?>. 
            <a href="http://open-classifieds.com/market/" target="_blank">
            <?=__("If you need any help please check our professional services")?></a>.
    </p>    
</div>

<?if ($msg):?>
    <div class="alert alert-warning"><?=$msg?></div>
    <?install::view('hosting')?>
<?endif?>

<form method="post" action="" class="well form-horizontal" >

    <div class="row">
        <div class="col-md-6">
            <h2>1. <?=__('Site Configuration')?></h2>

            <div class="form-group">
                
                <div class="col-md-6">
                    <label class="control-label"><?=__("Site Language")?></label>
                    <select class="form-control" name="LANGUAGE" onchange="window.location.href='?LANGUAGE='+this.options[this.selectedIndex].value">
                        <?php 
                        $languages = scandir("languages");
                        foreach ($languages as $lang) 
                        {    
                            if( strpos($lang,'.')==false && $lang!='.' && $lang!='..' )
                            {
                                $sel = ($lang==install::$locale) ? ' selected="selected"' : '';
                                echo "<option$sel value=\"$lang\">$lang</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                
                <div class="col-md-6">
                <label class="control-label"><?=__("Site URL");?>:</label>
                <input  type="text" size="75" name="SITE_URL" value="<?=install::request('SITE_NAME',install::$url)?>"  class="form-control" />
                </div>
            </div>

            <div class="form-group">
                
                <div class="col-md-6">
                <label class="control-label"><?=__("Site Name")?>:</label>
                <input  type="text" name="SITE_NAME" placeholder="<?=__("Site Name")?>" value="<?=install::request('SITE_NAME')?>" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                
                <div class="col-md-6">
                <label class="control-label"><?=__("Time Zone")?>:</label>
                <?=install::get_select_timezones('TIMEZONE',install::request('TIMEZONE',date_default_timezone_get()))?>
                </div>
            </div>
        


            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#install" data-toggle="tab"><?=__('New Install')?></a></li>
              <li><a href="#upgrade" data-toggle="tab"><?=__('Upgrade System')?></a></li>
            </ul>
             
            <div class="tab-content">

                <div class="tab-pane active" id="install">
                    <div class="form-group">
                        
                        <div class="col-md-6">
                            <label class="control-label"><?=__("Administrator email")?>:</label>
                            <input type="text" name="ADMIN_EMAIL" value="<?=install::request('ADMIN_EMAIL')?>" placeholder="your@email.com" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        
                        <div class="col-md-6">
                            <label class="control-label"><?=__("Admin Password")?>:</label>
                            <input type="text" name="ADMIN_PWD" value="<?=install::request('ADMIN_PWD')?>" class="form-control" />   
                        </div>
                    </div>

                    <div class="checkbox">
                        <label><input type="checkbox" name="SAMPLE_DB" checked /><?=__("Sample data")?></label>
                        <span class="help-block"><?=__("Creates few sample categories to start with")?></span>
                    </div>
                    
                </div>

                <div class="tab-pane" id="upgrade">
                    <div class="form-group">
                        
                        <div class="col-md-6">
                            <label class="control-label"><?=__("Hash Key")?>:</label>
                            <input type="text" name="HASH_KEY" value="<?=install::request('HASH_KEY')?>" class="form-control" />   
                            <span class="help-block"><?=__('You need the Hash Key to re-install. You can find this value if you lost it at')?> <code>/oc/config/auth.php</code></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-6">


            <h2>2. <?=__('Database Configuration')?></h2>

            <div class="form-group">
                
                <div class="col-md-6">
                <label class="control-label"><?=__("Host name")?>:</label>
                <input  type="text" name="DB_HOST" value="<?=install::request('DB_HOST','localhost')?>" class="form-control"  />
                </div>
            </div>

            <div class="form-group">
                
                <div class="col-md-6">
                <label class="control-label"><?=__("User name")?>:</label>
                <input  type="text" name="DB_USER"  value="<?=install::request('DB_USER','root')?>" class="form-control"   />
                </div>
            </div>

            <div class="form-group">
                
                <div class="col-md-6">
                <label class="control-label"><?=__("Password")?>:</label>
                <input type="text" name="DB_PASS" value="<?=install::request('DB_PASS')?>" class="form-control" />       
                </div>
            </div>

            <div class="form-group">
                
                <div class="col-md-6">
                <label class="control-label"><?=__("Database name")?>:</label>
                <input type="text" name="DB_NAME" value="<?=install::request('DB_NAME','openclassifieds')?>" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                
                <div class="col-md-6">
                <label class="control-label"><?=__("Database charset")?>:</label>
                <input type="text" name="DB_CHARSET" value="<?=install::request('DB_CHARSET','utf8')?>" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                
                <div class="col-md-6">
                <label class="control-label"><?=__("Table prefix")?>:</label>
                <input type="text" name="TABLE_PREFIX" value="<?=install::request('TABLE_PREFIX','oc2_')?>" class="form-control" />
                <span class="help-block"><?=__("Allows multiple installations in one database if you give each one a unique prefix")?>. <?=__("Only numbers, letters, and underscores")?>.</span>
                </div>
            </div>
            
        </div>

        <div class="col-md-12">
            <div class="form-actions">
                <input type="submit" name="action" id="submit" value="<?=__("Install")?>" class="btn btn-primary btn-lg" />
                <hr>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="OCAKU" checked="checked" />
                        <?=__("Ocacu classifieds community registration")?><br>
                        <a target="_blank" href="http://ocacu.com/en/terms.html"><?=__('Terms')?></a>
                    </label>
                </div>
            </div>
        </div>

    </div>            
</form>