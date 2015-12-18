<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DemoController extends Controller
{
    public function dynamicAction()
    {
        return $this->render('AcmeMainBundle:Demo:dynamic.html.twig');
    }

    public function staticAction()
    {
        return $this->render('AcmeMainBundle:Demo:static.html.twig');
    }
}
