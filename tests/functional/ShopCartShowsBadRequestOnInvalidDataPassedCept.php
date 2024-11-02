<?php

use Tests\FunctionalTester;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure an error page is displayed if invalid data is passed to cart page');

//invalid JSON at items
$I->amOnPage('/index.php?items=invalid');

$I->see('Bad Request');
$I->see('Sorry, but there is something wrong with your request: Invalid items');
