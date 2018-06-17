<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class ZipUpload
{
    /**
     * @Assert\NotBlank(message="Please, upload a not empty zip file")
     * @Assert\File(
     *     mimeTypes={ "application/zip" },
     *     mimeTypesMessage = "Please upload a valid zip file",
     *     maxSize = "20M"
     *     )
     */
    private $file;



    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile(File $file)
    {
        $this->file = $file;
    }



}