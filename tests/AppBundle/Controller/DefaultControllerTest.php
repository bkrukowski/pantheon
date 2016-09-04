<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEmpty($crawler->filter('#loader')->text());
        $this->assertEquals('aGallery', $crawler->filter('html')->attr('ng-app'));
    }

    public function testAccessDenied()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/accessdenied');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
        $this->assertEquals('Access denied', $crawler->filter('h1')->text());
    }
}
