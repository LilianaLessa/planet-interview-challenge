[![PHP-CS](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/phpcs.yml/badge.svg)](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/phpcs.yml)
[![PHPStan](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/phpstan.yml/badge.svg)](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/phpstan.yml)
[![Tests-Unit](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/tests_unit.yml/badge.svg)](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/tests_unit.yml)

## Dependencies

- PHP 7.4
- composer

## Running the App
- run `composer install`
- run `php -S localhost:8081 -t public` in another command line tab
- on your browser, access http://localhost:8081/index.php

#### Showing some cart items
To see some items on the cart page, you pass some date in the `items` query parameter on the  URI. 
This data should be a json encoded array of objects with the properties `price` and `expiration`, as the following example:

```json
[
  {"price":123,"expires":"never"},
  {"price":200,"expires":"60min"}
]
```

So, to show the cart with the two items from above, 
you need to access:

http://localhost:8081/index.php?items=[{"price":123,"expires":"never"},{"price":"test","expires":"60min"}]

Please notice that the `expires` property only accepts `never` and `60min` as its value, 
but you can try to mess up with then (or even with the whole data) to see what happens!

## Running the Tests
- run `composer install`, if you did not run it before.
- run `vendor/bin/codecept build`
- in another command line tab, run `php -S localhost:8081 -t public` 
- finally, in the first command line tab, run `vendor/bin/codecept run --steps`

If you want to run unit or functional tests separately, these are the commands: 

- **Unit**:  run `vendor/bin/codecept run unit --steps`
- **Functional**:  run `vendor/bin/codecept run functional --steps`

## Docs and resources used

- Smarty
  - https://smarty-php.github.io/smarty/stable/
- Route Handling
  - https://route.thephpleague.com/5.x/
  - https://docs.laminas.dev/laminas-diactoros/
- Logging
  - https://stackify.com/php-monolog-tutorial/
- Dependency Injection
  - https://github.com/PHP-DI/PHP-DI/blob/6.4/doc/getting-started.md/
- PHP-CS
  - https://pragmate.dev/php/phpcs/
- PHPStan
  - https://phpstan.org/user-guide/getting-started
- GitHub workflows
  - https://docs.github.com/en/actions/writing-workflows
  - Old project as reference
- HTML details
  - https://web.dev/articles/add-manifest
  - https://ogp.me

Most of the code was written from scratch, except for the basic usage of the 
**Route Handler**, **Dependency Injection** and **Logging** concepts 
and the templates for the error pages, which were copied from 
the **public/404.html** originally present on the repository. 

## Original README.md

Can be found [here](README_original.md).