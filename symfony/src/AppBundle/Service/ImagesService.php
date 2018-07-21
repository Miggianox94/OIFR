<?php
/**
 * Created by PhpStorm.
 * User: Miggianox94
 * Date: 27/05/2018
 * Time: 23:17
 */

namespace AppBundle\Service;


use AppBundle\Entity\ZipUpload;
use DarrynTen\Clarifai\Clarifai;
use Exception;
use Imagick;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validator\TraceableValidator;
use Throwable;
use ZipArchive;

class ImagesService
{

    private $uploadDirectory = '';
    private $uploadTmpDirectory = '';
    private $container;
    private $clarifai = null;
    protected $logger;

    public const CLIENT_API_KEY = 'b841b5c86816410db10c174dc26e6354';


    /**
     * ImagesService constructor.
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(ContainerInterface $container,LoggerInterface $logger)
    {
        $logger->debug("ImageService __construct START");
        $this->container = $container;
        $this->uploadDirectory = $this->container->getParameter('recognizepage')['zip_upload_directory'];
        $this->uploadTmpDirectory = $this->container->getParameter('recognizepage')['tmp_unzip_directory'];
        $this->clarifai = new Clarifai(ImagesService::CLIENT_API_KEY);
        $this->logger = $logger;
        $logger->debug("ImageService __construct END");
    }

    /**
     * @param Request $request
     * @param TraceableValidator $validator
     * @return JsonResponse
     */
    public function processUploadRequest(Request $request, TraceableValidator $validator):JsonResponse{
        $file = $request->files->get('inputFile');
        $zipUpload = new ZipUpload();

        if($request->isXmlHttpRequest()){
            $this->logger->info("ImageService-->processUploadRequest: recognize as Ajax request");

            if(is_null($file)){
                $status = array('status' => 'error','message' => "You must choice a file first");
                return new JsonResponse($status);
            }

            //In this case this is an Ajax request
            $zipUpload->setFile($file);
        }
        else{
            $this->logger->error("ImageService-->processUploadRequest: recognize as normal request! Only Ajax requests support");
            $status = array('status' => 'error','message' => "Unknown action, ajax expected");
            return new JsonResponse($status);
        }


        if(count($errors = $this->validateFile($zipUpload,$validator)) > 0){
            $this->logger->error("ImageService-->processUploadRequest: error in validate file");
            $status = array('status' => 'error','message' => "You must choice a valid file. " . $errors->__toString());
            return new JsonResponse($status);
        }

        $file = $zipUpload->getFile();

        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
        $this->logger->info("ImageService-->processUploadRequest: fileName=" . $fileName);
        $this->logger->info("ImageService-->processUploadRequest: moving file to directory=" . $this->uploadDirectory);
        // moves the file to the directory
        try{
            $file = $file->move(
                //$this->getParameter('recognizepage')['zip_upload_directory'],
                $this->uploadDirectory,
                $fileName
            );

            $this->unzipFile($file->getRealPath(),$this->uploadTmpDirectory);

            $this->logger->info("ImageService-->processUploadRequest: file unzipped");

            $result = $this->recognizeImages();

            $this->clearTmpDirectory();

            $this->logger->info("ImageService-->processUploadRequest: tmpDir cleared");

        }catch (
        Throwable $ex){
            $this->logger->error("ImageService-->processUploadRequest: Exception.\n{$ex->getTraceAsString()}");
            $status = array('status' => 'error','message' => $ex->getMessage());
            return new JsonResponse($status);
        }

        $status = array('status' => 'success','message' => 'File correctly uploaded', 'result' => $result);
        return new JsonResponse($status);
    }

    private function clearTmpDirectory(){
        array_map('unlink', glob($this->uploadTmpDirectory . "/*"));
    }

    /**
     * @return array
     * @throws \ImagickException
     */
    private function recognizeImages(){
        $fileList = array_slice(scandir($this->uploadTmpDirectory), 2);
        $finalResponse = array();
        foreach ($fileList as $image ){
            //TODO: validare $image con un validatore per vedere se è un'immagine
            $modelResult = $this->clarifai->getModelRepository()->predictPath(
                $this->uploadTmpDirectory . '/' . $image,
                \DarrynTen\Clarifai\Repository\ModelRepository::NSFW
            );
            $nsfwValue = 0.0;
            $sfwValue = 0.0;
            $base64Image = null;

            if(strcasecmp($modelResult['outputs'][0]['data']['concepts'][0]['name'],"NSFW") == 0){
                $nsfwValue = $modelResult['outputs'][0]['data']['concepts'][0]['value'];
                $sfwValue = $modelResult['outputs'][0]['data']['concepts'][1]['value'];
            }
            else{
                $nsfwValue = $modelResult['outputs'][0]['data']['concepts'][1]['value'];
                $sfwValue = $modelResult['outputs'][0]['data']['concepts'][0]['value'];
            }

            $imageResized = new Imagick($this->uploadTmpDirectory . '/' . $image);
            //$imageResized->resizeImage(75, 75, Imagick::FILTER_POINT, 1/*, true*/);
            //$imageResized->thumbnailImage(75,75);

            //$imagedata = file_get_contents($this->uploadTmpDirectory . '/' . $image);
            $base64Image = base64_encode($imageResized->getimageblob());
            $imageResized->clear();


            $finalResponse[] = array('Image' => $base64Image,'NSFW' => $nsfwValue, 'SFW' => $sfwValue, 'filename' => $image);
        }
        return $finalResponse;
    }

    /**
     * @param ZipUpload $file
     * @param ValidatorInterface $validator
     * @return mixed
     */
    private function validateFile(ZipUpload $file, ValidatorInterface $validator)
    {
        return $validator->validate($file);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @param $zipfile
     * @param string $dest
     * @return bool|ZipArchive
     * @throws Exception
     */
    private function unzipFile ($zipfile, $dest='.' )
    {
        $zip = new ZipArchive;
        if ( $zip->open( $zipfile ) )
        {
            for ( $i=0; $i < $zip->numFiles; $i++ )
            {
                $entry = $zip->getNameIndex($i);
                if ( substr( $entry, -1 ) == '/' ) continue; // skip directories

                $fp = $zip->getStream( $entry );
                $ofp = fopen( $dest.'/'.basename($entry), 'w' );

                if ( ! $fp )
                    throw new Exception('Unable to extract the file.');

                while ( ! feof( $fp ) )
                    fwrite( $ofp, fread($fp, 8192) );

                fclose($fp);
                fclose($ofp);
            }

            $zip->close();
        }
        else
            return false;

        return $zip;
    }


}