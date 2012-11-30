<?php

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