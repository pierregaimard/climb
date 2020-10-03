<?php

namespace Framework3\Service;

use Framework3\Config\ConfigBag;
use Framework3\Config\ConfigContainer;
use Framework3\Env\EnvContainer;
use Framework3\Exception\AppException;
use Framework3\Exception\AppNotFoundException;
use Framework3\FileReader\FileReader;
use Framework3\Env\EnvBag;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Exception;

class Container implements ContainerInterface
{
    private const SERVICE_CONFIG_FILE_PATH = 'lib/service';

    /**
     * Service arguments key used in service configuration files.
     */
    private const SERVICE_ARGUMENT_KEY = 'argument';

    /**
     * Config files container
     *
     * The config files must be called from the service container.
     *
     * @var EnvContainer
     */
    private EnvContainer $envContainer;

    /**
     * Config files container
     *
     * The config files must be called from the service container.
     *
     * @var ConfigContainer
     */
    private ConfigContainer $configContainer;

    /**
     * Service Container array
     *
     * @var array
     */
    private array $container = [];

    /**
     * Contains the services declaration from lib and app service configurations files.
     *
     * @var ConfigBag
     */
    private ConfigBag $servicesConfig;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->container[FileReader::class] = new FileReader();
        $this->setEnvContainer();
        $this->setConfigContainer();
        $this->servicesConfig = $this->configContainer->getConfig(
            self::SERVICE_CONFIG_FILE_PATH,
            true
        );
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $key Identifier of the entry to look for.
     *
     * @return mixed Entry.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     * @throws AppException
     */
    public function get($key)
    {
        if ($this->hasIdInContainer($key)) {
            return $this->container[$key];
        }

        if (!$this->has($key)) {
            throw new AppNotFoundException(
                sprintf('Service class "%s" not found', $key)
            );
        }

        if ($this->hasServiceConfig($key)) {
            $serviceConfig = $this->getServiceConfig($key);
        }

        if (isset($serviceConfig)) {
            $arguments = $this->getServiceArguments($serviceConfig[self::SERVICE_ARGUMENT_KEY]);
            $this->container[$key] = new $key(...$arguments);

            return $this->container[$key];
        }
        // If no config found, returns the service without configuration.
        $this->container[$key] = new $key();

        return $this->container[$key];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($key)` returning true does not mean that `get($key)` will not throw an exception.
     * It does however mean that `get($key)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $key Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($key): bool
    {
        return class_exists($key);
    }

    /**
     * Verify if the service already exists in the container.
     *
     * @param string $key
     *
     * @return bool
     */
    private function hasIdInContainer(string $key): bool
    {
        return array_key_exists($key, $this->container);
    }

    /**
     * Verify if the service config have been declared in `lib/service"` config file.
     *
     * @param string $key Service id
     *
     * @return bool
     */
    private function hasServiceConfig(string $key): bool
    {
        return $this->servicesConfig->has($key);
    }

    /**
     * Returns a service configuration who have been declared in `lib/service`
     *
     * @param string $key Service id
     *
     * @return array
     *
     * @throws AppException
     */
    private function getServiceConfig(string $key): array
    {
        if (!$this->servicesConfig->has($key)) {
            throw new AppException(
                AppException::TYPE_CONFIG,
                sprintf('Service configuration is missing in "%s" config file', self::SERVICE_CONFIG_FILE_PATH)
            );
        }

        return $this->servicesConfig->get($key);
    }

    /**
     * Retrieves dependencies, config files & arguments to pass to the service from service configuration.
     *
     * @param array     $arguments
     *
     * @return array|null
     *
     * @throws AppException
     */
    private function getServiceArguments(array $arguments): ?array
    {
        if (empty($arguments)) {
            return null;
        }

        $serviceArguments = [];

        foreach ($arguments as $argument) {
            switch ($argument) {
                // Service dependency
                case (substr($argument, 0, 1) === '@'):
                    $serviceArguments[] = $this->get(substr($argument, 1));
                    break;
                // Config file
                case preg_match('#^\{(.*)}$#', $argument) === 1:
                    $serviceArguments[] = $this->configContainer->getConfig(
                        substr($argument, 1, -1),
                        true
                    );
                    break;
                // Simple value
                default:
                    $serviceArguments[] = $argument;
            }
        }

        return $serviceArguments;
    }

    private function setEnvContainer(): void
    {
        $this->envContainer = new EnvContainer(
            $this->container[FileReader::class]
        );
    }

    /**
     * @throws Exception
     */
    private function setConfigContainer(): void
    {
        $this->configContainer = new ConfigContainer(
            $this->container[FileReader::class],
            $this->envContainer->getEnv()->get('CONFIG_DIR'),
            $this->envContainer->getEnv()->get('CONFIG_FILE_TYPE')
        );
    }

    /**
     * Shortcut to retrieve a configuration file
     *
     * @param string    $path
     * @param bool      $required
     *
     * @return ConfigBag
     *
     * @throws Exception
     */
    public function getConfig(string $path, $required = true): ConfigBag
    {
        return $this->configContainer->getConfig($path, $required);
    }

    /**
     * @return EnvBag
     */
    public function getEnv(): EnvBag
    {
        return $this->envContainer->getEnv();
    }
}
