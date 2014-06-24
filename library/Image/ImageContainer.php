<?php

namespace ImgManLibrary\Image;

use ImgManLibrary\BlobInterface;
use ImgManLibrary\Image\Exception;

class ImageContainer implements BlobInterface
{
    protected $blob;

    public function __construct($img)
    {
        try {
            $content = file_get_contents($img);
        } catch (\Exception $e){
            throw new Exception\FileNotFound($img . ' file not found');
        }

        if ($content === false) {
            throw new Exception\FileNotFound($img . ' file not found');

        } else {
            $this->setBlob($content);

        }
    }

    /**
     * @return string
     */
    public function getBlob()
    {
        return $this->blob;
    }

    /**
     * @param $blob
     * @return ImageEntity
     */
    public function setBlob($blob)
    {
        $this->blob = $blob;
        return $this;
    }
}