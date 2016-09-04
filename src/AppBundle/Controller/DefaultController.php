<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends ControllerBase
{
    /**
     * @Route("/", name="homepage")
     * @Route("/new-album", name="new_album")
     * @Route("/album-{albumId}", name="album")
     * @Route("album-{albumId}/photo-{photoId}", name="photo")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('@App/index.html.twig');
    }

    /**
     * @Route("/accessdenied", name="access_denied")
     */
    public function accessDeniedAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $result = $this->json([
                'success' => 'false',
                'error' => 'Access denied',
            ]);
            $result->setStatusCode(Response::HTTP_FORBIDDEN);

            return $result;
        }

        $result = new Response('<h1>Access denied</h1>');
        $result->headers->set('Content-Type', 'text/html');
        $result->setCharset('UTF-8');
        $result->setStatusCode(Response::HTTP_FORBIDDEN);

        return $result;
    }
}
