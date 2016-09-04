<?php

namespace AppBundle\Controller\Login;

use AppBundle\Controller\ControllerBase;
use AppBundle\Entity\Album;
use AppBundle\Entity\DeletedAlbumQueue;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Security("has_role('ROLE_ADMIN')")
 */
class AlbumController extends ControllerBase
{
    /**
     * @Route("/ajax/album/newAlbum")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function createNewAction(Request $request)
    {
        $name = $request->request->get('data')['name'] ?? null;
        $album = new Album();
        $album->setName($name);

        /** @var ValidatorInterface $validator */
        $validator = $this->get('validator');
        $tmpErrors = $validator->validate($album);
        if (count($tmpErrors)) {
            $errors = [];
            foreach ($tmpErrors as $error) {
                /** @var ConstraintViolationInterface $error */
                $errors[$error->getPropertyPath()] = [$error->getMessage()];
            }
            return $this->jsonFailure([
                'errors' => $errors
            ]);
        };

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($album);
        $manager->flush();
        return $this->jsonSuccess([
            'object' => [
                'id' =>  $album->getId(),
                'name' => $album->getName(),
            ],
        ]);
    }

    /**
     * @Route("/ajax/album/changeName")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function changeNameAction(Request $request)
    {
        $id = $request->request->get('albumId');
        /** @var Album $album */
        $album = $this->getDoctrine()->getRepository(Album::class)->findOneById($id);
        if (!$album) {
            return $this->json404();
        }
        $name = $request->request->get('name');
        $album->setName($name);
        /** @var ValidatorInterface $validator */
        $validator = $this->get('validator');
        $tmpErrors = $validator->validate($album);
        if (count($tmpErrors)) {
            $errors = [];
            foreach ($tmpErrors as $error) {
                /** @var ConstraintViolationInterface $error */
                $errors[$error->getPropertyPath()] = [$error->getMessage()];
            }
            return $this->jsonFailure([
                'errors' => $errors
            ]);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($album);
        $manager->flush();

        return $this->jsonSuccess([
            'object' => [
                'id' => $album->getId(),
                'name' => $album->getName(),
            ],
        ]);
    }

    /**
     * @Route("/ajax/album/removeAlbum")
     * @Method("POST")
     *
     * @throws \Throwable
     *
     * @param Request $request
     * @return Response
     */
    public function removeAlbumAction(Request $request)
    {
        $id = $request->request->get('albumId');
        /** @var Album $album */
        $album = $this->getDoctrine()->getRepository(Album::class)->findOneById($id);
        if (!$album) {
            return $this->json404();
        }

        $manager = $this->getDoctrine()->getManager();
        /** @var Connection $connection */
        $connection = $this->getDoctrine()->getConnection();
        $deleted = new DeletedAlbumQueue();
        $deleted->setAlbumId($album->getId());

        try {
            $connection->beginTransaction();
            $manager->persist($deleted);
            $manager->remove($album);
            $manager->flush();
            $connection->commit();
        } catch (\Throwable $exception) {
            $connection->rollBack();
            throw $exception;
        }

        return $this->jsonSuccess();
    }
}