<?php

namespace AppBundle\Controller;

use AppBundle\AppContext;
use AppBundle\Storage\PhotosInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class ControllerBase extends BaseController
{
    use AppContext;

    protected function render($view, array $parameters = array(), Response $response = null)
    {
        $preDefined = [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ];
        $parameters = array_merge($preDefined, $parameters);
        return parent::render($view, $parameters, $response);
    }

    protected function getDebugBar() : Profiler
    {
        return $this->container->get('profiler');
    }

    protected function jsonSuccess(array $data = [])
    {
        return $this->json(array_merge(['success' => true], $data));
    }

    protected function jsonFailure(array $data)
    {
        return $this->json(array_merge(['success' => false], $data));
    }

    protected function json404(array $data = ['success' => false])
    {
        $result = $this->json($data);
        $result->setStatusCode(Response::HTTP_NOT_FOUND);

        return $result;
    }

    protected function getPhotosStorage() : PhotosInterface
    {
        return $this->get('app.photo_storage');
    }
}