# Bilemo API

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0649ea7d8fe94df5bad817154ca53897)](https://app.codacy.com/gh/PierreThiollent/SnowTricks?utm_source=github.com&utm_medium=referral&utm_content=PierreThiollent/SnowTricks&utm_campaign=Badge_Grade_Settings)

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

```
$ composer install
```

### Config

In the project folder execute this command

```
$ cp .env .env.local
```

And then fill the DATABASE_URL variable

```
APP_ENV=dev
APP_SECRET=whatever
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
MAILER_DSN=smtp://localhost
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
$ cp .env.local .env.local.test
$ APP_ENV=test php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
```

You can ajust the database credentials in the .env.local.test file if you want. Then you can run this command which will
load Data fixtures and run the test suite.

```
$ composer test
```