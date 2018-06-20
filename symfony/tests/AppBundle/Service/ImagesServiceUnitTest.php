<?php


use AppBundle\Service\ImagesService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImagesServiceUnitTest extends KernelTestCase
{

    protected static $kernel;


    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();
    }

    /**
     *
     * @throws ReflectionException
     */
    public function testConstruct()
    {
        $container = static::$kernel->getContainer();
        $logger = $container->get('monolog.logger.appChannel');


        $class = new ReflectionClass("AppBundle\Service\ImagesService");
        $property = $class->getProperty("container");
        $property->setAccessible(true);

        $imageService = new ImagesService($container,$logger);

        $this->assertEquals(
            $container,
            $property->getValue($imageService)
        );

        $property = $class->getProperty("uploadDirectory");
        $property->setAccessible(true);
        $this->assertEquals(
            $container->getParameter('recognizepage')['zip_upload_directory'],
            $property->getValue($imageService)
        );

        $property = $class->getProperty("uploadTmpDirectory");
        $property->setAccessible(true);
        $this->assertEquals(
            $container->getParameter('recognizepage')['tmp_unzip_directory'],
            $property->getValue($imageService)
        );

        $property = $class->getProperty("clarifai");
        $property->setAccessible(true);

        $classClarifai = new ReflectionClass("DarrynTen\Clarifai\Request\RequestHandler");
        $propertyClarifai = $classClarifai->getProperty("apiKey");
        $propertyClarifai->setAccessible(true);


        $this->assertEquals(
            ImagesService::CLIENT_API_KEY,
            $propertyClarifai->getValue($property->getValue($imageService)->getRequest())
        );
    }

}