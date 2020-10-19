<?php

namespace Climb\Templating\Twig;

use Climb\Exception\AppException;
use Twig\Environment;

class TemplatingManager
{
    /**
     * @var EnvironmentManager
     */
    private EnvironmentManager $envManager;

    /**
     * @var ExtensionManager $extensionManager
     */
    private ExtensionManager $extensionManager;

    public function __construct(EnvironmentManager $envManager, ExtensionManager $extensionManager)
    {
        $this->envManager       = $envManager;
        $this->extensionManager = $extensionManager;
    }

    /**
     * @param array $flashes
     *
     * @return Environment
     *
     * @throws AppException
     */
    public function getEnvironment(array $flashes): Environment
    {
        $environment = $this->envManager->getEnvironment();
        $this->extensionManager->setExtensions($environment, $flashes);

        return $environment;
    }
}
