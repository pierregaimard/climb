<?php

namespace Framework3\Security\Annotation;

use Framework3\Annotation\AnnotationInterface;

class Security implements AnnotationInterface
{
    public const TAG = 'Security';

    /**
     * @var string[]
     */
    private array $roles;

    /**
     * @var bool
     */
    private bool $confirmPassword = false;

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return bool
     */
    public function isConfirmPassword(): bool
    {
        return $this->confirmPassword;
    }

    /**
     * @param bool $confirmPassword
     */
    public function setConfirmPassword(bool $confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }
}
