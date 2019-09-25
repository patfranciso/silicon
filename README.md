# silicon
## A Backend Programming Example In Codeigniter using Functional Programming

## Task:
A Simple User management system.

## Goal:
Design a convenient API that other developers would love to use for the tasks above. 

## Front end
 Vue.js, axios, jquery and bootstrap 3 

## User Stories
- As an admin I can add users — a user has a name.
- As an admin I can delete users.
- As an admin I can assign users to a group they aren’t already part of.
- As an admin I can remove users from a group.
- As an admin I can create groups.
- As an admin I can delete groups when they no longer have members.

MVC frameworks are powerful but they do not quite help to manage well when business rules are changing (as they often have to). 
This repo shows a rough example **Functional Programming** style ideas in PHP with **Either Monads (Railway Oriented Programming)** to capture business rules directly in your code. 

## Setup
  host : http://silicon.test [from your local virtual host]
  database name : silicon
  password  : silicon
  config alteration can be done in 
    application/config/config.php
    application/config/database.php

## Tests
-   **Phpunit** application/tests [run phpunit from command line]
-   **Mocha and Chai** js sample: *in browser* at http://silicon.test/assets/js/tests/index.html where I demo how I would test a function for an API call that uses a Promise

## Adding a new feature
To add a new feature all you have to do is:
  - create a module in the 'use_case' folder where you:
  - define the use case procedure as steps of checks and handles.
  - create procedure steps as functions (or callable object methods) so that the overall user case takes a Request and returns a Response.
  - make a presenter which will format the response according to delivery context.
Common functions can be added to the myfns_helper.php

## Login
  - usernames : admin / worker
  - passwords  : password
  