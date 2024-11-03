<?php

use Tests\FunctionalTester;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure an error page is displayed if a route is not found.');
$I->amOnPage('/invalid');

$I->see('Page Not Found');
$I->see('Sorry, but the page you were trying to view does not exist.');
