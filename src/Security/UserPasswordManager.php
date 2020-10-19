<?php

namespace Climb\Security;

use Climb\Config\ConfigBag;
use Climb\Exception\AppException;

class UserPasswordManager extends AbstractPasswordManager
{
    /**
     * Key used in configuration file to declare algorithm type.
     */
    private const CONFIG_ALGORITHM_KEY = 'algorithm';

    /**
     * Key used in configuration file to declare hashing options.
     */
    private const CONFIG_OPTION_KEY = 'option';

    /**
     * @var array
     */
    private array $config;

    /**
     * UserPasswordManager constructor.
     *
     * @param ConfigBag $config
     *
     * @throws AppException
     */
    public function __construct(ConfigBag $config)
    {
        $this->config = $config->get("password", true);
        parent::__construct($this->getConfigAlgorithm(), $this->getConfigOptions());
    }

    /**
     * @param string $password
     *
     * @return string|null
     */
    public function getPasswordHash(string $password): ?string
    {
        return parent::getPasswordHash($password);
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function isPasswordValid(string $password, string $hash): bool
    {
        return parent::isPasswordValid($password, $hash);
    }

    /**
     * Returns password hash algorithm set in lib/security configuration file.
     *
     * @return string
     *
     * @throws AppException
     */
    private function getConfigAlgorithm(): string
    {
        if (
            !array_key_exists(self::CONFIG_ALGORITHM_KEY, $this->config) ||
            $this->config[self::CONFIG_ALGORITHM_KEY] === null
        ) {
            throw new AppException(
                AppException::TYPE_SECURITY,
                "Password Hash Algorithm is missing",
                'See "lib/security" configuration file'
            );
        }

        if (
            !in_array(
                $this->config[self::CONFIG_ALGORITHM_KEY],
                [
                    'PASSWORD_DEFAULT',
                    'PASSWORD_BCRYPT',
                    'PASSWORD_ARGON2I',
                    'PASSWORD_ARGON2ID'
                ]
            )
        ) {
            throw new AppException(
                AppException::TYPE_SECURITY,
                "Invalid Password algorithm",
                sprintf(
                    'Configuration file declaration: "%s"',
                    $this->config[self::CONFIG_ALGORITHM_KEY]
                )
            );
        }

        return $this->config[self::CONFIG_ALGORITHM_KEY];
    }

    /**
     * Returns password hash options if exists, or null if not.
     *
     * @return array|null
     *
     * @throws AppException
     */
    private function getConfigOptions(): ?array
    {
        if (
            !array_key_exists(self::CONFIG_OPTION_KEY, $this->config) ||
            $this->config[self::CONFIG_OPTION_KEY] === null
        ) {
            return null;
        }

        if (!is_array($this->config[self::CONFIG_OPTION_KEY])) {
            throw new AppException(
                AppException::TYPE_SECURITY,
                'Password hash options declaration must be of ARRAY type.',
                'See lib/security configuration file.'
            );
        }

        return $this->config[self::CONFIG_OPTION_KEY];
    }
}
