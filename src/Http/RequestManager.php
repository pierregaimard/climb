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
        $server = $this->getServer();

        return new Request(
            $server,
            filter_input_array(INPUT_GET),
            filter_input_array(INPUT_POST),
            filter_input_array(INPUT_COOKIE),
            $this->getFiles(),
            $session,
            str_replace('?' . $server['QUERY_STRING'], '', $server['REQUEST_URI']),
            $server['REQUEST_METHOD']
        );
    }

    /**
     * @return array
     */
    private function getServer(): array
    {
        return filter_var_array($_SERVER);
    }

    /**
     * @return array
     */
    private function getFiles(): array
    {
        return filter_var_array($_FILES);
    }
}
