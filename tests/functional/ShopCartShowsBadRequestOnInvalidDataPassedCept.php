<?php

use Tests\FunctionalTester;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure an error page is displayed if invalid data is passed to cart page');

//invalid JSON at items
$I->amOnPage('/index.php?items=invalid');

$I->see('Bad Request');
$I->see('Sorry, but there is something wrong with your request: Invalid items');

//invalid Expiration at items
$I->amOnPage('/index.php?items=%5B%7B%22price%22%3A123%2C%22expires%22%3A%22someday%22%7D%2C+%7B%22price%22%3A200%2C%22expires%22%3A%2260min%22%7D%5D');

$I->see('Bad Request');
$I->see('Sorry, but there is something wrong with your request: Invalid expiration');
