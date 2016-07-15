<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('change themes and theme options for each theme');


$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');


// Splash

$I->wantTo('activate Splash theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','splash');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/theme/options');
$I->fillField('#home_slogan','Homepage site slogan');
$I->click('submit');
$I->see('Theme configuration updated');

$I->amOnPage('/');
$I->see('Homepage site slogan');

$I->amOnPage('/oc-panel/theme/options');
$I->fillField('#home_slogan','Search and place ads easily with open classifieds');
$I->click('submit');
$I->see('Theme configuration updated');

$I->amOnPage('/');
$I->see('Search and place ads easily with open classifieds');


// Reclassifieds

$I->wantTo('activate Reclassifieds3 theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','reclassifieds');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');


// See on responsive theme

$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','responsive');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('listing_slider','1');
$I->click('submit');
$I->amOnPage('/all');
$I->seeElement('.well.featured-posts');
$I->seeElement('.glyphicon.glyphicon-chevron-right');
$I->seeElement('.glyphicon.glyphicon-chevron-left');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('listing_slider','0');
$I->click('submit');


// Newspaper

$I->wantTo('activate Newspaper theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','newspaper');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');


// Czsale

$I->wantTo('activate Czsale theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','czsale');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');


// Ocean

$I->wantTo('activate Ocean Premium theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','ocean');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');


// Modern Deluxe

$I->wantTo('activate moderndeluxe3 theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','moderndeluxe');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');


// Olson

$I->wantTo('activate Olson theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','olson');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');


// Kamaleon

$I->wantTo('activate Kamaleon theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','kamaleon');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');


// Jobdrop

$I->wantTo('activate Jobdrop theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','jobdrop');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');


// Yummo

$I->wantTo('activate Yummo theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','yummo');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('listing_slider','1');
$I->click('submit');
$I->amOnPage('/all');
$I->seeElement('.featured-posts');
$I->seeElement('.glyphicon.glyphicon-chevron-right');
$I->seeElement('.glyphicon.glyphicon-chevron-left');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('listing_slider','0');
$I->click('submit');


// Basecamp_free

$I->wantTo('activate Basecamp_free theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','basecamp_free');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');


// Basecamp

$I->wantTo('activate Basecamp theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','basecamp');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','0');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->dontSeeElement('.breadcrumb');
$I->amOnPage('/oc-panel/theme/options');
$I->selectOption('breadcrumb','1');
$I->click('submit');
$I->see('Theme configuration updated');
$I->amOnPage('/housing');
$I->seeElement('.breadcrumb');

// Mobile

$I->wantTo('activate Mobile theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','mobile');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');


// Default

$I->wantTo('activate Default theme again');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','default');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

