<?php

namespace Climb\Security;

class TokenManager extends AbstractPasswordManager
{
    public function __construct()
    {
        parent::__construct('PASSWORD_BCRYPT');
    }

    /**
     * returns a token from $keyword
     *
     * @param string $keyword
     *
     * @return string|null
     */
    public function getToken(string $keyword): ?string
    {
        return $this->getPasswordHash($keyword);
    }

    /**
     * verify if token is valid.
     *
     * @param string $keyword
     * @param string $hash
     *
     * @return bool
     */
    public function isTokenValid(string $keyword, string $hash)
    {
        return $this->isPasswordValid($keyword, $hash);
    }
}
