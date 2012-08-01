<?php

namespace Acme\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\Page;

class LoadSimpleCmsData implements FixtureInterface, ContainerAwareInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();

        $basepath = $this->container->getParameter('symfony_cmf_simple_cms.basepath');

        $basepath = explode('/', $basepath);
        $rootname = array_pop($basepath);
        $basepath = implode('/', $basepath);

        if ('' === $basepath) {
            $basepath = '/';
        } else if ($session->nodeExists($basepath)) {
            $session->removeItem($basepath);
        }

        $base = $manager->find(null, $basepath);

        $root = $this->createPage($manager, $base, $rootname, 'homepage', 'Welcome to the CMF Standard Edition', 'This is should get you started with the Symfony CMF.');
        $this->createPage($manager, $root, 'about', 'About us', 'Some information about us', 'The about us page with some content');
        $contact = $this->createPage($manager, $root, 'contact', 'Contact', 'A contact page', 'Please send an email to symfony-cmf-devs@groups.google.com');
        $this->createPage($manager, $contact, 'map', 'Map', 'A map page', 'Have a look at the map to find us.');
        $this->createPage($manager, $contact, 'team', 'Team', 'A team page', 'Our team consists of C, M and F.');

        $manager->flush();
    }

    /**
     * @return Page instance with the specified information
     */
    protected function createPage(ObjectManager $manager, $parent, $name, $label, $title, $body)
    {
        $page = new Page();
        $page->setPosition($parent, $name);
        $page->setLabel($label);
        $page->setTitle($title);
        $page->setBody($body);

        $manager->persist($page); // do persist before binding translation

        return $page;
    }
}
