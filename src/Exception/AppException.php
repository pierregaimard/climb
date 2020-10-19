<?php

namespace Climb\Exception;

use Exception;

class AppException extends Exception
{
    public const TYPE_APP_EXCEPTION_TYPE           = 1;
    public const TYPE_CONFIG                       = 2;
    public const TYPE_ANNOTATION_REFLECTION_CLASS  = 3;
    public const TYPE_ANNOTATION_PARSER            = 4;
    public const TYPE_ANNOTATION_MANAGER_EXCEPTION = 5;
    public const TYPE_CLASS_FINDER                 = 6;
    public const TYPE_ROUTER                       = 7;
    public const TYPE_CONTROLLER                   = 8;
    public const TYPE_SECURITY                     = 9;
    public const TYPE_NOT_FOUND                    = 10;
    public const TYPE_ENV                          = 11;
    public const TYPE_ENV_PARSER                   = 12;
    public const TYPE_ORM                          = 13;

    /**
     * Exceptions titles
     */
    private const TYPES = [
        1 =>  'InvalidTypeException',
        2 =>  'ConfigurationFileException',
        3 =>  'AnnotationReflectionClassException',
        4 =>  'AnnotationParserException',
        5 =>  'AnnotationManagerException',
        6 =>  'ClassFinderException',
        7 =>  'RouterException',
        8 =>  'ControllerException',
        9 =>  'SecurityException',
        10 => 'NotFoundException',
        11 => 'EnvFileException',
        12 => 'EnvParserException',
        13 => 'OrmException',
    ];

    /**
     * AppException type.
     * Valid type required
     *
     * @var string
     */
    protected string $type;

    /**
     * Exception message detail (optional)
     *
     * @var string|null
     */
    private ?string $messageDetail;

    /**
     * @param string      $type
     * @param string      $message
     * @param string|null $messageDetail
     * @param int         $code
     *
     * @throws AppException
     */
    public function __construct(
        string $type,
        string $message = '',
        string $messageDetail = null,
        int $code = 0
    ) {
        parent::__construct($message, $code);
        $this->setType($type);
        $this->messageDetail = $messageDetail;
    }

    /**
     * @param string $type
     *
     * @throws AppException
     */
    protected function setType(string $type): void
    {
        if (!array_key_exists($type, self::TYPES)) {
            throw new AppException(
                self::TYPE_APP_EXCEPTION_TYPE,
                "Illegal type used to throw AppException",
                sprintf('Type: "%s"', $type),
                1
            );
        }

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getMessageDetail(): ?string
    {
        return $this->messageDetail;
    }

    /**
     * personalized message display
     *
     * @return string|void
     */
    public function __toString()
    {
        // Todo: use twig templating to display AppException

        $messageDetail = ($this->getMessageDetail()) ? "<br />" . "DETAIL: " . $this->getMessageDetail() : null;

        return
            "<hr />" .
            "<strong>" . self::TYPES[$this->getType()] . "</strong>" .
            "<hr />" .
            "CODE: " . $this->getCode() .
            "<br />" .
            "MESSAGE: " . $this->getMessage() .
            $messageDetail .
            "<br />" .
            "FILE: " . $this->getFile() .
            "<br />" .
            "LINE: " . $this->getLine() .
            "<br />" .
            "TRACE: " . nl2br($this->getTraceAsString()) .
            "<hr />"
        ;
    }
}
