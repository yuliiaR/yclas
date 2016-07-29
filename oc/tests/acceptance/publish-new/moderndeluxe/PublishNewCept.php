<?php 
$I = new AcceptanceTester($scenario);
$I->am('a user');
$I->wantTo('publish a new ad');


$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->wantTo('activate moderndeluxe theme');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','moderndeluxe');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->click('Logout'); 


$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','gazzasdasd@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');

$I->amOnPage('/publish-new.html');
$I->see('Publish new advertisement');
$I->fillField('#title',"User ad on moderndeluxe");
$I->click('.select-category');
$I->fillField('category','18');
$I->fillField('location','4');
$I->fillField('#description','This is a new user ad on moderndeluxe theme');
$I->attachFile('input[type="file"]', 'photo.jpg');
$I->fillField('#phone','99885522');
$I->fillField('#address','barcelona');
$I->fillField('#price','25');
$I->fillField('#website','https://www.user.com');
$I->click('submit_btn');

$I->see('Advertisement is posted. Congratulations!');

$I->amOnPage('/apartment/user-ad-on-moderndeluxe.html');
$I->see('User ad on moderndeluxe');
$I->see('This is a new user ad on moderndeluxe theme');
$I->see('Barcelona');

$I->click('Logout'); 


$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->wantTo('activate Default theme again');
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','default');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->click('Logout'); 