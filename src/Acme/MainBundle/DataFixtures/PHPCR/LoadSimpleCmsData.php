<?php

namespace Acme\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use PHPCR\SessionInterface;

use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\Page;

class LoadSimpleCmsData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 50;
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
        } else {
            if ($session->nodeExists($basepath)) {
                $session->removeItem($basepath);
            }
        }

        $base = $this->createPath($session, $basepath);
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
    protected function createPage($manager, $parent, $name, $label, $title, $body)
    {
        $page = new Page();
        $page->setPosition($parent, $name);
        $page->setLabel($label);
        $page->setTitle($title);
        $page->setBody($body);

        $manager->persist($page); // do persist before binding translation

        return $page;
    }

    /**
     * Create a node and it's parents, if necessary.  Like mkdir -p.
     *
     * TODO: clean this up once the id generator stuff is done as intended
     *
     * @param SessionInterface $session
     * @param string $path  full path, like /cms/navigation/main
     * @return \PHPCR\NodeInterface the (now for sure existing) node at path
     */
    public function createPath(SessionInterface $session, $path)
    {
        $current = $session->getRootNode();

        $segments = preg_split('#/#', $path, null, PREG_SPLIT_NO_EMPTY);
        foreach ($segments as $segment) {
            if ($current->hasNode($segment)) {
                $current = $current->getNode($segment);
            } else {
                $current = $current->addNode($segment);
            }
        }

        return $current;
    }
}
