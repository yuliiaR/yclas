<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('CRUD widgets');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');


// Categories
$I->wantTo('create a widget');
$I->amOnPage('/oc-panel/widget');
$I->click('Create');
$I->selectOption('placeholder','sidebar');
$I->click('Save changes');

// See on default theme
$I->amOnPage('/');
$I->seeElement('.col-md-3.col-sm-12.col-xs-12');

// See on splash theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','splash');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/');
$I->seeElement('.col-md-3.col-sm-12.col-xs-12');

// See on moderndeluxe3 theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','moderndeluxe');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/');
$I->seeElement('.col-md-3.col-sm-12.col-xs-12');

// See on olson theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','olson');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/all');
$I->seeElement('.col-md-3.col-sm-12.col-xs-12');

// See on reclassifieds3 theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','reclassifieds');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/');
$I->seeElement('.col-md-3.col-sm-12.col-xs-12');

// See on kamaleon theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','kamaleon');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/');
$I->seeElement('.col-md-3.col-sm-12.col-xs-12');

// See on responsive theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','responsive');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/');
$I->seeElement('.widget-header');

// See on czsale theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','czsale');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/');
$I->seeElement('.col-md-3.col-sm-12.col-xs-12');

// See on jobdrop theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','jobdrop');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('sidebar_position','right');
$I->click('submit');
$I->amOnPage('/all');
$I->seeElement('.col-md-3.col-sm-12.col-xs-12');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('sidebar_position','none');
$I->click('submit');

// See on ocean theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','ocean');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/');
$I->seeElement('.col-md-3.col-sm-12.col-xs-12');

// See on yummo theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','yummo');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/');
$I->seeElement('.col-md-3.col-sm-12.col-xs-12');

// See on newspaper theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','newspaper');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/');
$I->seeElement('#sidebar');

// See on basecamp theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','basecamp');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/all');
$I->seeElement('.Widget_Search');

// Back to default theme
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','default');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');


// Delete
$I->amOnPage('/oc-panel/widget');
$I->click('button[class="btn btn-primary btn-xs pull-right"]');
$I->seeElement('.glyphicon.glyphicon-trash');
$I->click('a[class="btn btn-danger pull-left"]');









$I->amOnPage('/');
$I->click('Logout');























