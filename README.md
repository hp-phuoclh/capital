# Capital Project 

## Introduction

* This is a Capital coffee admin app, with enhancemenets and many modules pre-made.

## Requirements
- PHP >= 7.2.x
- MariaDB
- NodeJS >= 14.x 

## Installation

Clone the repository

If you are in UNIX system, you can execute bellow command

    1) sudo chmod -R 777 install.sh
    2) ./install.sh

For linking storage folder in public

    php artisan storage:link

API run the passport:install command.

    php artisan passport:install

**Command list**

    cp .env.example .env
    composer install
    php artisan key:generate
    php artisan migrate
    php artisan passport:install
    php artisan storage:link

## Other Important Commands
- To fix php coding standard issues run - composer format
- To perform various self diagnosis tests on your Laravel application. run - php artisan self-diagnosis
- To clear all cache run - composer clear-all
- To built Cache run - composer cache-all
- To clear and built cache run - composer cc

## Logging In

`php artisan db:seed` adds three users with respective roles. The credentials are as follows:

* Administrator: `admin@admin.com`
Password: Contact admin 

## Require skills for development
- PHP
- MYSQL
- AWS
- Pusher
- Swagger
- WorkFlow
