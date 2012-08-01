# Symfony CMF Standard Edition

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

The next step is to setup the database:

    app/console doctrine:database:create
    app/console doctrine:phpcr:init:dbal
    app/console doctrine:phpcr:register-system-node-types
    app/console doctrine:phpcr:fixtures:load

## Access by web browser

Create an apache virtual host entry along the lines of

    <Virtualhost *:80>
        Servername simple-cms.lo
        DocumentRoot /path/to/symfony-cmf/symfony-cmf-standard/web
        <Directory /path/to/symfony-cmf/symfony-cmf-standard>
            AllowOverride All
        </Directory>
    </Virtualhost>

And add an entry to your hosts file for "simple-cms.lo"

If you are running Symfony2 for the first time, run http://simple-cms.lo/config.php to ensure your
system settings have been setup inline with the expected behaviour of the Symfony2 framework.

Then point your browser to http://simple-cms.lo/app_dev.php or http://simple-cms.lo

Functional tests are written with PHPUnit. Note that Bundles and Components are tested independently.

    app/console doctrine:phpcr:workspace:create standard_test
    phpunit -c app

[![Build Status](https://secure.travis-ci.org/symfony-cmf/symfony-cmf-standard.png?branch=master)](http://travis-ci.org/symfony-cmf/symfony-cmf-standard)
