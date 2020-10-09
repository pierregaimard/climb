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
        $response->setContent('403 Forbidden');

        return $response;
    }

    /**
     * @return Response
     */
    public function unauthorized()
    {
        $response = new Response(Response::STATUS_UNAUTHORIZED);
        $response->setContent('401 Unauthorized');

        return $response;
    }
}
