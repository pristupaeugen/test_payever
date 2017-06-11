Payever test project
====================

A Symfony project created on June 10, 2017, 11:09 pm.

Start application
=================

To start application you need:

1. Copy `app/config/parameters.yml.dist` to `app/config/parameters.yml`. Add your credentials to `app/config/parameters.yml`.

2. Make composer installation:

`$ composer install`

3. Update database structure:

`$ php bin/console doctrine:schema:update --force`

4. Execute database migrations:

`$ php bin/console doctrine:migrations:migrate`

Execute unittests
=================

From project directory make command:

`$ vendor/bin/phpunit`