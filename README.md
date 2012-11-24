# Symfony CMF Standard Edition [![Build Status](https://secure.travis-ci.org/symfony-cmf/symfony-cmf-standard.png?branch=master)](http://travis-ci.org/symfony-cmf/symfony-cmf-standard)

### You will need:
  * Git 1.6+
  * PHP 5.3.3+
  * php5-intl
  * phpunit 3.6+ (optional)
  * composer

## Get the code

    curl -s http://getcomposer.org/installer | php --
    php composer.phar create-project symfony-cmf/standard-edition path/to/install

This will fetch the main project and all it's dependencies.

The next step is to setup the database, if you want to use Sqlite as your database backend just go ahead and run the following:

    app/console doctrine:database:create
    app/console doctrine:phpcr:init:dbal
    app/console doctrine:phpcr:register-system-node-types
    app/console doctrine:phpcr:fixtures:load

If you prefer to use another database backend, for example MySQL, run the Symfony configurator (point your browser 
to /web/config.php) or set your database connection parameters in app/config/parameters.yml. Make sure you leave
the 'database_path' property at 'null' in order to use another driver than SQLite. Leaving the field blank in the
web-configurator should set it to 'null'.

## Access by web browser

Create an apache virtual host entry along the lines of

    <Virtualhost *:80>
        Servername symfony-cmf-standard.lo
        DocumentRoot /path/to/symfony-cmf/symfony-cmf-standard/web
        <Directory /path/to/symfony-cmf/symfony-cmf-standard>
            AllowOverride All
        </Directory>
    </Virtualhost>

And add an entry to your hosts file for "symfony-cmf-standard.lo"

If you are running Symfony2 for the first time, run http://symfony-cmf-standard.lo/config.php to ensure your
system settings have been setup inline with the expected behaviour of the Symfony2 framework.

Then point your browser to http://symfony-cmf-standard.lo/app_dev.php or http://symfony-cmf-standard.lo

Functional tests are written with PHPUnit. Note that Bundles and Components are tested independently.

    app/console doctrine:phpcr:workspace:create standard_test
    phpunit -c app

## Configuration

You can use the same steps as for the Symfony2 Standard Edition to check and configure the application:
https://github.com/symfony/symfony-standard#2-checking-your-system-configuration

Note that if you want to improve performance you can enable the caching system:
https://github.com/symfony-cmf/symfony-cmf-standard/blob/master/app/config/parameters.yml#L10

This will enable caching of storage API lookups into the file system. However it can easily be
configured to instead use any of the caching backends supported by Doctrine Common and exposed
by LiipDoctrineCacheBundle:
https://github.com/liip/LiipDoctrineCacheBundle