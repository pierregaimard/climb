<?php

namespace Climb\Env;

use Climb\Filesystem\FileReader;

class EnvContainer
{
    /**
     * @var EnvManager
     */
    private EnvManager $envManager;

    /**
     * @var FileReader
     */
    private FileReader $fileReader;

    /**
     * @var EnvBag|null
     */
    private ?EnvBag $env = null;

    public function __construct(FileReader $fileReader)
    {
        $this->envManager = new EnvManager($fileReader);
        $this->fileReader = $fileReader;
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
        $modePackageDir = __DIR__ . '/../../../../../';
        if ($this->fileReader->has($this->envManager->getFilePath($modePackageDir))) {
            return $this->envManager->getEnvData($modePackageDir);
        }

        // Dev mode
        $modeDevDir = __DIR__ . '/../../';
        if (!$this->fileReader->has($this->envManager->getFilePath($modeDevDir))) {
            return $this->envManager->getEnvData();
        }

        return $this->envManager->getEnvData($modeDevDir);
    }
}
