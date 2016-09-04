<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends ControllerBase
{
    /**
     * @Route("/ajax/isLogged", name="is_logged")
     * @param Request $request
     * @return Response
     */
    public function isLoggedAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $responseArray = $user ? [
            'isLogged' => true,
            'username' => $user->getUsername(),
        ] : [
            'isLogged' => false
        ];

        $response = new Response(json_encode($responseArray));
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');

        return $response;
    }
}