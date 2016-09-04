<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Entity\Photo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AlbumController extends ControllerBase
{
    use PhotoTrait;

    /**
     * @Route("/ajax/album/getList")
     *
     * @param Request $request
     * @return Response
     */
    public function getAlbumListAction(Request $request)
    {
        /** @var Album[] $albums */
        $albums = $this->getDoctrine()->getRepository(Album::class)->findAll();
        $result = [];
        foreach ($albums as $album) {
            /** @var Photo $photo */
            $photo = $this->getDoctrine()->getRepository(Photo::class)->findOneByAlbumId($album->getId());
            $result[] = [
                'id' => $album->getId(),
                'name' => $album->getName(),
                'thumb' => $photo
                    ? $this->generateUrlToThumb($photo)
                    : '',
            ];
        }
        return $this->json(['albums' => $result]);
    }

    /**
     * @Route("/ajax/album")
     *
     * @param Request $request
     * @return Response
     */
    public function getAlbumAction(Request $request)
    {
        $get = $request->query;
        $albumId = $get->get('id');
        $screenWidth = $get->get('screenWidth');
        $screenHeight = $get->get('screenHeight');

        if (!$albumId || !$screenHeight || !$screenWidth) {
            return $this->json404();
        }
        /** @var Album $album */
        $album = $this->getDoctrine()->getRepository(Album::class)->findOneById($albumId);

        $result = [
            'album' => [
                'id' => $album->getId(),
                'name' => $album->getName(),
            ],
            'images' => [],
        ];

        foreach ($this->getDoctrine()->getRepository(Photo::class)->findByAlbumId($albumId) as $photo) {
            /** @var Photo $photo */
            $size = $this->getPhotosStorage()->resizeDimensions(
                $screenWidth,
                $screenHeight,
                $photo->getWidth(),
                $photo->getHeight()
            );
            $result['images'][] = [
                'id' => $photo->getId(),
                'w' => $size['width'],
                'h' => $size['height'],
                'src' => $this->generateUrlToPhoto($photo, $screenWidth, $screenHeight),
                'thumb' => $this->generateUrlToThumb($photo),
                'original' => $this->generateUrlToOriginalPhoto($photo),
                'album_id' => $photo->getAlbumId(),
            ];
        }

        return $this->json($result);
    }
}