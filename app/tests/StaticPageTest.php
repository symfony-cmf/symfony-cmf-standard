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

        $this->assertCount(1, $crawler->filter(sprintf('h1:contains("%s")', $title)));
    }

    public function contentDataProvider()
    {
        return array(
            array('/service/about', 'Some information about us'),
            array('/service/contact', 'A contact page'),
        );
    }
}
