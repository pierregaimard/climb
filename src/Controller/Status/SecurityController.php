<?php

namespace Framework3\Controller\Status;

use Framework3\Http\Response;
use Framework3\Controller\AbstractController;

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
