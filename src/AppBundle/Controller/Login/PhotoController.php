<?php

namespace AppBundle\Controller\Login;

use AppBundle\Controller\ControllerBase;
use AppBundle\Controller\PhotoTrait;
use AppBundle\Entity\DeletedPhotosQueue;
use AppBundle\Entity\Photo;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("has_role('ROLE_ADMIN')")
 */
class PhotoController extends ControllerBase
{
    use PhotoTrait;

    const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
    ];

    /**
     * @Route("/ajax/photo/uploadOne")
     * @Method("POST")
     */
    public function uploadOneAction(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        $albumId = $request->query->get('albumId');
        if (is_null($file) || !$albumId) {
            return $this->json404();
        }

        if (!$file->isValid()) {
            return $this->jsonFailure(['errorName' => 'Unexpected error']);
        }

        if (!$this->checkMimeType($file->getMimeType())) {
            return $this->jsonFailure(['errorName' => 'Invalid type of file!']);
        }

        $photo = $this->getPhotosStorage()->putFile($albumId, $file);

        return $this->jsonSuccess([
            'result' => [
                'id' => $photo->getId(),
                'w' => $photo->getWidth(),
                'h' => $photo->getHeight(),
                'src' => $this->generateUrlToOriginalPhoto($photo),
                'thumb' => $this->generateUrlToThumb($photo),
                'original' => $this->generateUrlToOriginalPhoto($photo),
                'album_id' => $photo->getAlbumId(),
            ],
        ]);
    }

    /**
     * @Route("/ajax/photo/removeOne")
     * @Method("POST")
     *
     * @throws \Throwable
     *
     * @param Request $request
     * @return Response
     */
    public function removeOneAction(Request $request) : Response
    {
        $id = $request->request->get('photoId');
        /** @var Photo $photo */
        $photo = $this->getDoctrine()->getRepository(Photo::class)->findOneById($id);
        if (!$photo) {
            return $this->json404();
        }

        $manager = $this->getDoctrine()->getManager();
        /** @var Connection $connection */
        $connection = $this->getDoctrine()->getConnection();
        $deleted = new DeletedPhotosQueue();
        $deleted->setPhotoId($id);

        try {
            $connection->beginTransaction();
            $manager->persist($deleted);
            $manager->remove($photo);
            $manager->flush();
            $connection->commit();
        } catch (\Throwable $exception) {
            $connection->rollBack();
            throw $exception;
        }

        return $this->jsonSuccess();
    }

    private function checkMimeType(string $mimeType) : bool
    {
        return in_array($mimeType, static::ALLOWED_MIME_TYPES);
    }
}