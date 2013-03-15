<? 
include 'checks.php';
include 'functions.php';
include 'header.php';?>


<?if ($succeed){?>

<? include 'install.php';?>



<div class="page-header">
	<h1><?=__("Welcome to");?> Open Classifieds <?=__("installation");?></h1>
	<p>
		<?=__("Welcome to the super easy and fast installation");?>. 
		<?if (SAMBA){?>
			<a href="http://open-classifieds.com/download/" target="_blank">
			<?=__("If you need any help please check our professional services");?></a>.
		<?}?>
	</p>	
</div>

<?if ($msg){?>
	<div class="alert alert-warning"><?=$msg;?></div>
<?hostingAd();}?>

<form method="post" action="" class="well" >
<fieldset>

<div class="control-group">
	<label class="control-label"><?=__("Site Language");?></label>
	<div class="controls">
       <select name="LANGUAGE">

		<option value="en_EN">en_EN</option>
		    <?
		    $languages = scandir("languages");
		    foreach ($languages as $lang) {
			    
			    if( strpos($lang,'.')==false && $lang!='.' && $lang!='..' ){
				    if ($lang==$locale_language)  $sel= "selected=selected";
				    else $sel = "";
				    echo "<option $sel value=\"$lang\">$lang</option>";
			    }
		    }
		    ?>
		</select>
	</div>
</div>



<h2><?=__('Database Configuration');?></h2>

<div class="control-group">
	<label class="control-label"><?=__("Host name");?>:</label>
	<div class="controls">
	<input  type="text" name="DB_HOST" value="<?=cP('DB_HOST','localhost')?>" class="span6"  />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("User name");?>:</label>
	<div class="controls">
	<input  type="text" name="DB_USER"  value="<?=cP('DB_USER','root')?>" class="span6"   />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Password");?>:</label>
	<div class="controls">
	<input type="password" name="DB_PASS" value="<?=cP('DB_PASS');?>" class="span6" />		
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Database name");?>:</label>
	<div class="controls">
	<input type="text" name="DB_NAME" value="<?=cP('DB_NAME','openclassifieds')?>"  class="span6"  />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Database charset");?>:</label>
	<div class="controls">
	<input type="text" name="DB_CHARSET" value="<?=cP('DB_CHARSET','utf8')?>"  class="span6"   />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Table prefix");?>:</label>
	<div class="controls">
	<input type="text" name="TABLE_PREFIX" value="<?=cP('TABLE_PREFIX','oc_')?>" class="text-medium" />
	<span class="help-block"><?=__("Allows multiple installations in one database if you give each one a unique prefix");?>. <?=__("Only numbers, letters, and underscores");?>.</span>
	</div>
</div>


<div class="control-group">
	<label class="checkbox"><input type="checkbox" name="SAMPLE_DB"  value="1" /><?=__("Sample data");?></label>
	<span class="help-block"><?=T_("Creates few sample categories and posts");?></span>
</div>

<h2><?=__('Basic Configuration');?></h2>

<div class="control-group">
	<label class="control-label"><?=__("Site Name");?>:</label>
	<div class="controls">
	<input  type="text" name="SITE_NAME" placeholder="<?=__("Site Name");?>" value="<?=cP('SITE_NAME')?>" class="span6" />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Time Zone");?>:</label>
	<div class="controls">
	<?=get_select_timezones('TIMEZONE',cP('TIMEZONE',date_default_timezone_get()));?>
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Admin email");?>:</label>
	<div class="controls">
	<input type="text" name="ADMIN=__MAIL" value="<?=cP('ADMIN_EMAIL','your@email.com')?>"  class="span6" />
	</div>
</div>

<div class="control-group">
	<label class="control-label"><?=__("Admin Password");?>:</label>
	<div class="controls">
	<input type="password" name="ADMIN_PWD" value="<?=cP('ADMIN_PWD')?>" class="span6" />	
	</div>
</div>

<div class="control-group">
	<label class="checkbox">
		<input type="checkbox" name="OCAKU" value="1" checked="checked" />
		<?=__("Ocaku registration");?> <a target="_blank" href="http://ocacu.com/en/terms.html">
			<?=__('Terms');?></a>
	</label>
	<span class="help-block"><?=__("Allow site to be in Ocaku, classifieds community (recommended)");?></span>	
</div>

<?if (SAMBA){?>
	<div class="control-group">
		<label class="checkbox"><input checked="checked" type="checkbox" id="terms" name="terms" value="1" />  <?=__("I accept the license terms");?>. </label>
		<span class="help-block"><a href="http://www.gnu.org/licenses/gpl.txt" target="_blank">GPL v3</a>
		<?=__("Please read the following license agreement and accept the terms to continue");?>
		</span>
	</div>
<?}else{?>
	<input type="hidden" id="terms" name="terms" value="1" />
<?}?>

<input type="submit" name="action" id="submit" value="<?=__("Install");?>" class="btn btn-primary btn-large" />

</fieldset>
</form>

<?
}//if requirements succeed

else {?>

<div class="alert alert-error"><?=$msg;?></div>
<?hostingAd(); }?>

<?if (SAMBA){?>
<div class="hero-unit">
	<h2>Upgrade now!</h2>
	<p>Just for $69.90, Installation, commercial license, premium support, 13 premium themes and much more.</br>
		<a class="btn btn-primary btn-large" href="http://open-classifieds.com/download/"><i class=" icon-shopping-cart icon-white"></i> Buy now!</a>
	</p>
</div>
<?}?>


<?include 'footer.php';?>