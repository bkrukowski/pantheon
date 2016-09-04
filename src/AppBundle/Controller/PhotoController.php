<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class PhotoController extends ControllerBase
{
    use PhotoTrait;

    /**
     * @Route("/photo/{id}-{token}/{width}x{height}", name="get_photo")
     * @Method("GET")
     *
     * @param $id
     * @param $token
     * @param $width
     * @param $height
     * @return Response
     */
    public function getPhotoAction($id, $token, $width, $height)
    {
        if ($height > $width) {
            $tmp = $height;
            $height = $width;
            $width = $tmp;
        }

        $allowed = $this->getAllowedResolutions();
        $done = false;
        foreach ($allowed as list($tmpWidth, $tmpHeight)) {
            if ($width <= $tmpWidth && $height <= $tmpHeight) {
                $width = $tmpWidth;
                $height = $tmpHeight;
                $done = true;
                break;
            }
        }

        if (!$done) {
            list($width, $height) = end($allowed);
            reset($allowed);
        }

        return $this
            ->getPhotosStorage()
            ->getResizedPhotoResponse($id, $token, $width, $height);
    }

    /**
     * @Route("/photo/{id}-{token}", name="get_original_photo")
     * @Method("GET")
     *
     * @param $id
     * @param $token
     * @return Response
     */
    public function getOriginalPhotoAction($id, $token)
    {
        return $this
            ->getPhotosStorage()
            ->getPhotoResponse($id, $token);
    }
}