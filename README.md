# Bilemo API

This project is carried out as part of my course PHP / Symfony Application Developer at OpenClassroom.

## Requirements 🔧

- Composer
- PHP (^8.0)
- MySQL

### Project setup

```
$ git clone https://github.com/PierreThiollent/bilemo-api-platform.git
```

```
$ cd bilemo-api-platform
```

### Config

In the project folder execute this command

```
$ cp .env .env.local
```

And then fill the DATABASE_URL variable. Some env variables are required for api platform

```
APP_ENV=dev
APP_SECRET=whatever

DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7

###> nelmio/cors-bundle ###
CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'
###< nelmio/cors-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=5327ecb05eae9c7d493859874b165c39
###< lexik/jwt-authentication-bundle ###v
```

You can now install the dependencies and generate keys for JWT authentication

```
$ composer install
$ php bin/console lexik:jwt:generate-keypair
```

### DB setup

To create the database and all the tables execute this command :

```
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
```

### Load Data fixtures

```
php bin/console doctrine:fixtures:load --no-interaction
```

### Run

To run the development server you can use the Symfony CLI or the PHP built-in server.

```
$ symfony serve
or
$ php -S localhost:8000
```

### Test

To run the test you need to setup the test environment.

```
$ cp .env.local .env.test.local
$ APP_ENV=test php bin/console doctrine:database:create
$ APP_ENV=test php bin/console doctrine:schema:update --force
$ composer recipes:install phpunit/phpunit --force -v
```

This env variable should be present in test env

```
KERNEL_CLASS=App\Kernel
```

You can ajust the database credentials in the .env.local.test file if you want. Then you can run this command which will
load Data fixtures and run the test suite.

```
$ composer test
```