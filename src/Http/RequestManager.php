<?php

namespace Framework3\Http;

use Framework3\Http\Session\Session;

class RequestManager
{
    /**
     * @return Request
     */
    public function getFromGlobals(): Request
    {
        $session = new Session();
        $session->initialize();

        return new Request(
            filter_input_array(INPUT_SERVER),
            filter_input_array(INPUT_GET),
            filter_input_array(INPUT_POST),
            filter_input_array(INPUT_COOKIE),
            filter_var_array($_FILES),
            $session
        );
    }
}
