<?php

namespace Acme\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Yaml\Parser;
use Symfony\Cmf\Bundle\SimpleCmsBundle\DataFixtures\LoadCmsData;

use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\Page;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\MultilangRoute;
use Symfony\Cmf\Bundle\MenuBundle\Document\MultilangMenuItem;

class LoadSimpleCmsData extends LoadCmsData
{
    public function getOrder()
    {
        return 5;
    }

    protected function getData()
    {
        $yaml = new Parser();
        return $yaml->parse(file_get_contents(__DIR__.'/../../Resources/data/page.yml'));
    }

    public function load(ObjectManager $dm)
    {
        parent::load($dm);

        $yaml = new Parser();
        $data = $yaml->parse(file_get_contents(__DIR__ . '/../../Resources/data/external.yml'));

        $basepath = $this->container->getParameter('symfony_cmf_simple_cms.basepath');
        $home = $dm->find(null, $basepath);

        $route = new MultilangRoute();
        $route->setPosition($home, 'dynamic');
        $route->setDefault('_controller', 'AcmeMainBundle:Demo:dynamic');

        $dm->persist($route);

        foreach ($data['static'] as $name => $overview) {
            $menuItem = new MultilangMenuItem();
            $menuItem->setName($name);
            $menuItem->setParent($home);
            if (!empty($overview['uri']))
                $menuItem->setUri($overview['uri']);
            else
            {
                $menuItem->setRoute($dm->find(null, $basepath.'/'.$overview['route'])->getId());
            }
            
            $dm->persist($menuItem);
            foreach ($overview['label'] as $locale => $label) {
                $menuItem->setLabel($label);
                if ($locale) {
                    $dm->bindTranslation($menuItem, $locale);
                }
            }
        }

        $dm->flush();
    }
}
