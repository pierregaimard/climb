<?php

namespace Climb\Security;

use Climb\Exception\AppException;

class AbstractPasswordManager
{
    /**
     * Password hash algorithm
     *
     * @var string
     */
    private string $algorithm;

    /**
     * Password hash options
     *
     * @var array|null
     */
    private ?array $options;

    /**
     * PasswordManager constructor.
     *
     * @param string        $algorithm
     * @param array|null    $options
     */
    public function __construct(string $algorithm, ?array $options = null)
    {
        $this->algorithm = $algorithm;
        $this->options   = $options;
    }

    /**
     * @param string $password
     *
     * @return string|null
     */
    public function getPasswordHash(string $password): ?string
    {
        if ($this->getOptions() === null) {
            return password_hash(
                $password,
                constant($this->getAlgorithm()),
            );
        }

        return password_hash(
            $password,
            constant($this->getAlgorithm()),
            $this->getOptions()
        );
    }

    /**
     * @param string $password  Given password to verify
     * @param string $hash      User Hashed password.
     *
     * @return bool
     */
    public function isPasswordValid(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * @return string
     */
    private function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    /**
     * @param string $algorithm
     *
     * @throws AppException
     */
    protected function setAlgorithm(string $algorithm): void
    {
        if (
            !in_array(
                $algorithm,
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
                sprintf('Algorithm: "%s"', $algorithm)
            );
        }

        $this->algorithm = $algorithm;
    }

    /**
     * @return array|null
     */
    private function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * @param array|null $options
     */
    protected function setOptions(?array $options): void
    {
        $this->options = $options;
    }
}
