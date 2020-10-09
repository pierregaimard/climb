<?php

namespace Framework3\Exception;

use Psr\Container\NotFoundExceptionInterface;

class AppNotFoundException extends AppException implements NotFoundExceptionInterface
{
    public function __construct(string $message = "", string $messageDetail = null)
    {
        parent::__construct(self::TYPE_NOT_FOUND, $message, $messageDetail, 1000);
    }
}
