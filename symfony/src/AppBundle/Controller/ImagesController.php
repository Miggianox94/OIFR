<?php

namespace AppBundle\Controller;


use AppBundle\Entity\ZipUpload;
use AppBundle\Form\ZipUploadType;
use AppBundle\Service\ImagesService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class ImagesController extends Controller
{

    private $logger;


    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger){
        $this->logger = $logger;
    }

    /**
     * @Route("/",name="homepage")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homePageAction(Request $request)
    {
        $this->logger->info("homePageAction matched");
        return $this->render('main/homePage.html.twig');

    }

    /**
     * @Route("/recognize",name="recognizePage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recognizePageAction(Request $request)
    {
        $this->logger->info("recognizePageAction matched");
        $zipUpload = new ZipUpload();

        $form = $this->createForm(ZipUploadType::class, $zipUpload);

        $this->logger->debug("recognizePageAction--> form created");

        return $this->render('main/recognizePage.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/aboutPage",name="aboutPage")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutPageAction(Request $request)
    {
        $this->logger->info("aboutPageAction matched");
        return $this->render('main/about.html.twig');

    }


    /**
     * @Route("/recognizeUpload",name="recognizeUpload")
     * @param Request $request
     * @param ImagesService $imagesService
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recognizeUploadPageAction(Request $request, ImagesService $imagesService)
    {
        $this->logger->info("recognizeUploadPageAction matched");

        return $imagesService->processUploadRequest($request,$this->get('validator'));


    }

}