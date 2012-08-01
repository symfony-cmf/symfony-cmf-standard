<?php

namespace Sandbox;

class StaticPageTest extends WebTestCase
{
    /**
     * @dataProvider contentDataProvider
     */
    public function testContent($url, $title)
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertCount(1, $crawler->filter(sprintf('h1:contains("%s")', $title)), 'Page does not contain an h1 tag with: '.$title);
    }

    public function contentDataProvider()
    {
        return array(
            array('/', 'Welcome to the CMF Standard Edition'),
            array('/about', 'Some information about us'),
            array('/contact', 'A contact page'),
            array('/contact/map', 'A map page'),
            array('/contact/team', 'A team page'),
        );
    }
}
