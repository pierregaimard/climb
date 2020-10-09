<?php

namespace Framework3\Controller\Status;

use Framework3\Http\Response;
use Framework3\Controller\AbstractController;

class NotFoundController extends AbstractController
{
    /**
     * Returns a 404 Not found Response.
     *
     * @return Response
     */
    public function notFound()
    {
        $response = new Response(Response::STATUS_NOT_FOUND);
        $response->setContent('404 Not Found');

        return $response;
    }
}
