<?php

namespace Climb\Controller\Status;

use Climb\Http\Response;
use Climb\Controller\AbstractController;

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
        $response->setContent($this->render('404_not_found.html.twig'));

        return $response;
    }
}
