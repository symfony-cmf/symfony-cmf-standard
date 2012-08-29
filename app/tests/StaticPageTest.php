<?php

namespace Sandbox;

class StaticPageTest extends WebTestCase
{
    public function testRedirectToHomepage()
    {
        $client = $this->createClient();

        $client->request('GET', '/');

        $this->assertEquals(301, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertEquals('http://localhost/en', $client->getRequest()->getUri());
    }

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
            array('/en', 'Welcome to the CMF Standard Edition'),
            array('/en/about', 'Some information about us'),
            array('/en/contact', 'A contact page'),
            array('/en/contact/map', 'A map of a location in the US'),
            array('/de/contact/map', 'Eine Karte von einem Ort in Deutschland'),
            array('/en/contact/team', 'A team page'),
        );
    }
}
