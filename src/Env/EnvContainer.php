<?php

namespace Framework3\Env;

class EnvContainer
{
    /**
     * @var EnvManager
     */
    private EnvManager $envManager;

    /**
     * @var EnvBag|null
     */
    private ?EnvBag $env = null;

    public function __construct()
    {
        $this->envManager = new EnvManager();
    }

    /**
     * @return EnvBag
     */
    public function getEnv(): EnvBag
    {
        if ($this->env instanceof EnvBag) {
            return $this->env;
        }

        $this->env = new EnvBag($this->loadEnv());
        return $this->env;
    }

    /**
     * @return array
     */
    private function loadEnv(): ?array
    {
        // Composer Package mode
        if (file_exists($this->envManager->getFilePath(__DIR__ . '/../../../../../'))) {
            return $this->envManager->getEnvData(__DIR__ . '/../../../../../');
        }

        // Dev mode
        $path = $this->envManager->getFilePath(__DIR__ . '/../../');

        if (!file_exists($path)) {
            return $this->envManager->getEnvData();
        }

        return $this->envManager->getEnvData(__DIR__ . '/../../');
    }
}
