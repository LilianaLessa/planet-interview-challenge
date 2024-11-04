# Planet interview challenge

**Picture the scene:** you're working your first day at the new job, all eager to get to work with all these shiny new technologies that make development a breeze and improve the quality of life. And then you are handed with your first assignment.
But the project that you open is not the company's biggest, brightest, most important project.
It's not even the second most important one.
With rising dread, the dawning of an understanding building deep inside your mind finally gives form to those two words, the words most abhorred by all programmers, software developers, coders, hackers and testers out in the world:

Legacy system.

You've been handed a legacy system.
A summary briefing takes place, which actually doesn't take long since all they can tell you is that the previous person working it was some guy called Gurd and nobody has any idea when Gurd actually even resigned. The handover is over in almost an instant.

You are left with simple instructions: "Make the tests pass, and provide a tester with instructions on how to install it and run the tests. I don't care what you do or how you do it, **as long as the tests pass.** Also, if you want to demonstrate your mastery over PHP and legacy projects, go ahead and make the project more presentable and up-to-date - **as long as the tests pass.**"

## Further instructions
- Fork this repository, and push all the changes that you make to your forked copy.
- The instructions for how to run the app and the tests should be added to the repository's README.md file (this one).
- **Important:** the file README.md **must** also describe which parts of your solution were not made from scratch - this includes but not limited to usign generative AI such as ChatGPT, copying code from StackOverflow or other online resource or using any other existing resources such as Google search results. If the entirety of your submission was made from the ground up by yourself, please state this as well.
- Once finished, reach out to your recruitment contact in Planet to let them know you have finished the assignment.

---
[![PHP-CS](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/phpcs.yml/badge.svg)](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/phpcs.yml)
[![PHPStan](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/phpstan.yml/badge.svg)](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/phpstan.yml)
[![Tests-Unit](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/tests_unit.yml/badge.svg)](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/tests_unit.yml)
[![Tests-Functional](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/tests_functional.yml/badge.svg)](https://github.com/LilianaLessa/planet-interview-challenge/actions/workflows/tests_functional.yml)
[![Coverage Status](https://coveralls.io/repos/github/LilianaLessa/planet-interview-challenge/badge.svg?branch=main)](https://coveralls.io/github/LilianaLessa/planet-interview-challenge)
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
- Code Coverage
  - https://docs.coveralls.io
  - https://github.com/php-coveralls/php-coveralls?tab=readme-ov-file#github-actions

Most of the code was written from scratch, except for the basic usage of the 
**Route Handler**, **Dependency Injection** and **Logging** concepts 
and the templates for the error pages, which were copied from 
the **public/404.html** originally present on the repository. 

## Original README.md

Can be found [here](README_original.md).