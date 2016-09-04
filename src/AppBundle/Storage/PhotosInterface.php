<?php

namespace AppBundle\Storage;

use AppBundle\Entity\Photo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

interface PhotosInterface
{
    const TOKEN_LENGTH = 20;

    public function putFile(int $albumId, UploadedFile $file) : Photo;

    public function getPhotoResponse($id, $token) : Response;

    public function getResizedPhotoResponse($id, $token, $width, $height) : Response;

    public function resizeDimensions($screenWidth, $screenHeight, $width, $height);
}