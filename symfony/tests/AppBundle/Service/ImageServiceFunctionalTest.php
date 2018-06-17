<?php

namespace Tests\AppBundle\Service;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageServiceFunctionalTest extends WebTestCase
{

    public function testProcessUploadRequestImgFile()
    {
        $client = static::createClient();

        $photo = new UploadedFile(
            $client->getKernel()->getContainer()->getParameter('tests')['assetDir'].'AppBundle\testAssets\img\nerowhatsap.jpg',
            'nerowhatsap.jpg',
            'image/jpeg'
        );
        $client->request(
            'POST',
            '/recognizeUpload',
            array(),
            array('inputFile' => $photo),
            array('HTTP_Content-Type' => 'application/json')
        );

        $JSON_response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
        $this->assertNotEmpty($JSON_response);
        $this->assertNotEmpty($JSON_response["message"]);
    }

}