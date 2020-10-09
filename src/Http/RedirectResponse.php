<?php

namespace Framework3\Http;

class RedirectResponse extends Response
{
    /**
     * @var string
     */
    private string $routePath;

    public function __construct(string $routePath, array $data = null)
    {
        parent::__construct();

        if (is_array($data)) {
            $this->getData()->setAll($data);
        }

        $this->routePath = $routePath;
    }

    public function send(): void
    {
        $this->setSessionData();
        $this->setHeader("Location: " . $this->routePath);
    }
}
