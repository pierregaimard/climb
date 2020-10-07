<?php

namespace Framework3\Http;

class RedirectResponse extends Response
{
    /**
     * @var string
     */
    private string $routePath;

    public function __construct(string $routePath, array $data)
    {
        parent::__construct();
        $this->getData()->setAll($data);
        $this->routePath = $routePath;
    }

    public function send(): void
    {
        $this->setSessionData();
        header("Location: " . $this->routePath);
    }
}
