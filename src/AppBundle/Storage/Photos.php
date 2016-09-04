<?php

namespace AppBundle\Storage;

use AppBundle\Entity\MimeType;
use AppBundle\Entity\Photo;
use AppBundle\Helper\RandomizerInterface;
use Doctrine\DBAL\Connection;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class Photos implements PhotosInterface
{
    private $randomizer;

    private $doctrine;

    private $storagePath;

    private $cachePath;

    public function __construct(
        RegistryInterface $doctrine,
        RandomizerInterface $randomizer,
        string $path, string $cachePath
    ) {
        $this->doctrine = $doctrine;
        $this->randomizer = $randomizer;
        $this->storagePath = str_replace('/', DIRECTORY_SEPARATOR, realpath($path));
        $this->cachePath = str_replace('/', DIRECTORY_SEPARATOR, $cachePath);
    }

    public function putFile(int $albumId, UploadedFile $file) : Photo
    {
        if (!$file->isValid()) {
            throw new \RuntimeException($file->getErrorMessage());
        }

        $photo = new Photo();
        $photo->setToken($this->randomizer->randomString(static::TOKEN_LENGTH));
        $photo->setAlbumId($albumId);
        $photo->setOriginalFilename($file->getClientOriginalName());
        list($width, $height) = getimagesize($file->getRealPath());
        $photo->setWidth($width);
        $photo->setHeight($height);
        $photo->setMimeTypeId(
            $this->getMimeTypeByName($file->getMimeType())->getId()
        );

        $manager = $this->doctrine->getManager();
        /** @var Connection $connection */
        $connection = $manager->getConnection();

        try {
            $connection->beginTransaction();
            $manager->persist($photo);
            $manager->flush();
            $fileSystem = new Filesystem();
            $directory = $this->getDirectoryForPhoto($photo);
            if (!$fileSystem->exists($directory)) {
                $fileSystem->mkdir($directory);
            }
            $file->move($directory, $photo->getId());
            $connection->commit();
        } catch (\Throwable $exception) {
            $connection->rollBack();
            throw $exception;
        }

        return $photo;
    }

    public function getPhotoResponse($id, $token) : Response
    {
        $repository = $this->doctrine->getRepository(Photo::class);
        /** @var Photo $row */
        $row = $repository->findOneBy([
            'id' => $id,
            'token' => $token,
        ]);

        if (!$row) {
            return new Response('404', Response::HTTP_NOT_FOUND);
        }

        return $this->getResponseForPhotoAndPath(
            $row,
            $this->getDirectoryForPhoto($row) . DIRECTORY_SEPARATOR . $id
        );
    }

    public function getResizedPhotoResponse($id, $token, $width, $height) : Response
    {
        $repository = $this->doctrine->getRepository(Photo::class);
        /** @var Photo $photo */
        $photo = $repository->findOneBy([
            'id' => $id,
            'token' => $token,
        ]);

        if (!$photo) {
            return new Response('404', Response::HTTP_NOT_FOUND);
        }

        $fileSystem = new Filesystem();

        $directory = $this->getCacheDirectoryForPhoto($photo);
        if (!$fileSystem->exists($directory)) {
            $fileSystem->mkdir($directory);
        }

        $filePath = $directory . DIRECTORY_SEPARATOR . $this->getCacheNameForPhoto($photo, $width, $height);
        if (!$fileSystem->exists($filePath)) {
            $originalPath = $this->getDirectoryForPhoto($photo) . DIRECTORY_SEPARATOR . $id;
            $imagick = new \Imagick($originalPath);
            $newSize = $this->resizeDimensions($width, $height, $photo->getWidth(), $photo->getHeight());
            $imagick->resizeImage($newSize['width'], $newSize['height'], \Imagick::FILTER_LANCZOS, 1);
            $imagick->setImageCompressionQuality(70);
            $imagick->writeImage($filePath);
        }

        return $this->getResponseForPhotoAndPath(
            $photo,
            $filePath
        );
    }

    public function resizeDimensions($screenWidth, $screenHeight, $width, $height)
    {
        if (!$screenHeight || !$height || !$width || !$screenWidth)
        {
            throw new \RuntimeException('Should be greater than 0! ' . print_r(func_get_args(), true));
        }
        $screenWidth = $screenHeight = max($screenWidth, $screenHeight);
        $return = array('width' => $width, 'height' => $height);

        if ($width/$height > $screenWidth/$screenHeight && $width > $screenWidth)
        {
            $return['width'] = $screenWidth;
            $return['height'] = $screenWidth/$width * $height;
        }
        else if ($height > $screenHeight)
        {
            $return['width'] = $screenHeight/$height * $width;
            $return['height'] = $screenHeight;
        }

        return $return;
    }

    private function getResponseForPhotoAndPath(Photo $photo, string $path)
    {
        $response = new Response();
        $response->setContent(new class ($path) {
            private $path;

            public function __construct (string $path)
            {
                $this->path = $path;
            }

            public function __toString()
            {
                return file_get_contents($this->path);
            }
        });
        $response->headers->set('Content-Type', $this->getMimeTypeById($photo->getMimeTypeId()));

        return $response;
    }

    private function getMimeTypeById(int $id) : string
    {
        /** @var MimeType $result */
        $result = $this->doctrine->getRepository(MimeType::class)->findOneById($id);
        if (!$result) {
            throw new \RuntimeException("There is no mime-type with id={$id}!");
        }

        return $result->getName();
    }

    private function getMimeTypeByName(string $name) : MimeType
    {
        $repository = $this->doctrine->getRepository(MimeType::class);
        if ($result = $repository->findOneByName($name)) {
            return $result;
        }

        $result = new MimeType();
        $result->setName($name);

        $manager = $this->doctrine->getManager();
        $manager->persist($result);
        $manager->flush();

        return $result;
    }

    private function getDirectoryForPhoto(Photo $photo) : string
    {
        return $this->storagePath . DIRECTORY_SEPARATOR . $photo->getAlbumId();
    }

    private function getCacheDirectoryForPhoto(Photo $photo) : string
    {
        return $this->cachePath . DIRECTORY_SEPARATOR . $photo->getAlbumId();
    }

    private function getCacheNameForPhoto(Photo $photo, int $width, int $height) : string
    {
        return $photo->getId() . '-' . $width . 'x' . $height;
    }
}