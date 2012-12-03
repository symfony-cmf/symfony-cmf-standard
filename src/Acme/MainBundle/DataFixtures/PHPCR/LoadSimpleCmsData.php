<?php

namespace Acme\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use PHPCR\Util\NodeHelper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\Page;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\MultilangPage;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\MultilangRoute;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\MultilangRedirectRoute;
use Symfony\Cmf\Bundle\MenuBundle\Document\MultilangMenuItem;

class LoadSimpleCmsData extends ContainerAware implements FixtureInterface
{
    public function load(ObjectManager $dm)
    {
        $session = $dm->getPhpcrSession();

        $basepath = explode('/', $this->container->getParameter('symfony_cmf_simple_cms.basepath'));
        $rootname = array_pop($basepath);
        $basepath = implode('/', $basepath);

        if ($session->nodeExists($basepath)) {
            $session->removeItem($basepath);
        }

        NodeHelper::createPath($session, $basepath);
        $base = $dm->find(null, $basepath);

        $root = $this->createPage($dm, $base, $rootname, 'Homepage', array('en' => array('Welcome to the CMF Standard Edition', 'This is should get you started with the Symfony CMF.'), 'de' => array('Willkommen zur CMF Standard Edition', 'Dies sollte Ihnen einen Einstieg in das Symfony CMF bieten.')));
        $this->createPage($dm, $root, 'about', 'About us', array('' => array('Some information about us', 'The about us page with some content')));
        $contact = $this->createPage($dm, $root, 'contact', 'Contact', array('' => array('A contact page', 'Please send an email to symfony-cmf-devs@groups.google.com')));
        $this->createPage($dm, $contact, 'map', 'Map', array('en' => array('A map of a location in the US', 'Have a look at the map to find us.'), 'de' => array('Eine Karte von einem Ort in Deutschland', 'Hier können Sie uns finden.')));
        $this->createPage($dm, $contact, 'team', 'Team', array('' => array('A team page', 'Our team consists of C, M and F.')));

        $this->createMenuItem($dm, $root, 'link', 'http://cmf.symfony.com', array('en' => 'Website', 'de' => 'Webseite'));

        $route = new MultilangRedirectRoute();
        $route->setPosition($root, 'demo');
        $route->setUri('http://cmf.liip.ch');
        $dm->persist($route);

        $this->createMenuItem($dm, $root, 'demo_redirect', $route, array('en' => 'Demo', 'de' => 'Demo'));

        $route = new MultilangRoute();
        $route->setPosition($root, 'dynamic');
        $route->setDefault('_controller', 'AcmeMainBundle:Demo:dynamic');

        $dm->persist($route);

        $this->createMenuItem($dm, $root, 'hardcoded_dynamic', $route, array('en' => 'Dynamic', 'de' => 'Dynamisch'));

        $this->createMenuItem($dm, $root, 'hardcoded_static', 'static', array('en' => 'Static', 'de' => 'Statisch'));

        $dm->flush();
    }

    /**
     * @return Page instance with the specified information
     */
    protected function createPage(ObjectManager $dm, $parent, $name, $label, array $content)
    {
        $page = new MultilangPage();
        $page->setPosition($parent, $name);
        $page->setLabel($label);

        $dm->persist($page);
        foreach ($content as $locale => $data) {
            $page->setTitle($data[0]);
            $page->setBody($data[1]);
            if ($locale) {
                $dm->bindTranslation($page, $locale);
            }
        }

        return $page;
    }

    /**
     * @return MenuItem instance with the specified information
     */
    protected function createMenuItem(ObjectManager $dm, $parent, $name, $target, array $content)
    {
        $menuItem = new MultilangMenuItem();
        $menuItem->setPosition($parent, $name);
        if (is_object($target)) {
            $menuItem->setRoute($target->getPath());
        } else {
            $menuItem->setUri($target);
        }

        $dm->persist($menuItem);
        foreach ($content as $locale => $label) {
            $menuItem->setLabel($label);
            if ($locale) {
                $dm->bindTranslation($menuItem, $locale);
            }
        }

        return $menuItem;
    }
}
