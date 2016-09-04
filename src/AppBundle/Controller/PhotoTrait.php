<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;

trait PhotoTrait
{
    protected function generateUrlToOriginalPhoto(Photo $photo) : string
    {
        return $this->generateUrl('get_original_photo', [
            'id' => $photo->getId(),
            'token' => $photo->getToken(),
        ]);
    }

    protected function generateUrlToPhoto(Photo $photo, int $screenWidth, int $screenHeight) : string
    {
        return $this->generateUrl('get_photo', [
            'id' => $photo->getId(),
            'token' => $photo->getToken(),
            'width' => $screenWidth,
            'height' => $screenHeight,
        ]);
    }

    protected function generateUrlToThumb(Photo $photo) : string
    {
        return $this->generateUrlToPhoto($photo, ...$this->getAllowedResolutions()[0]);
    }

    protected function getAllowedResolutions() : array
    {
        return [
            [400, 400],
            [800, 600],
            [1024, 768],
            [1280, 800],
            [1280, 1024],
            [1366, 768],
            [1920, 1080],
            [2560, 1440],
            [3840, 2160],
        ];
    }
}