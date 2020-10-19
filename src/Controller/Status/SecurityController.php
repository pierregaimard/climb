<?php

namespace Climb\Controller\Status;

use Climb\Http\Response;
use Climb\Controller\AbstractController;

class SecurityController extends AbstractController
{
    /**
     * @return Response
     */
    public function forbidden()
    {
        $response = new Response(Response::STATUS_FORBIDDEN);
        $response->setContent($this->render('403_forbidden.html.twig'));

        return $response;
    }

    /**
     * @return Response
     */
    public function unauthorized()
    {
        $response = new Response(Response::STATUS_UNAUTHORIZED);
        $response->setContent($this->render('401_unauthorized.html.twig'));

        return $response;
    }
}
