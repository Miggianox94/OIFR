<?php
/**
 * Created by PhpStorm.
 * User: Miggianox94
 * Date: 05/06/2018
 * Time: 23:20
 */

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImagesControllerTest extends WebTestCase
{
    public function testHomePage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $mainTitle = $client->getKernel()->getContainer()->getParameter('homepage')['mainTitle'];
        $this->assertContains($mainTitle, $crawler->filter('.container h1')->text());
    }
}