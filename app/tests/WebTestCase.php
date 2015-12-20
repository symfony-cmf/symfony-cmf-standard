<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    protected static $fixturesLoaded = false;

    public function setUp()
    {
        if (self::$fixturesLoaded) {
            return;
        }

        $this->loadFixtures(array(
            'Acme\MainBundle\DataFixtures\PHPCR\LoadSimpleCmsData',
        ), null, 'doctrine_phpcr');

        self::$fixturesLoaded = true;
    }
}
