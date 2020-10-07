<?php

namespace Framework3\Http;

class XmlResponse extends Response
{
    /**
     * @param array $status
     * @param string $contentType
     * @param string $charset
     * @param string $content
     */
    public function __construct(
        $status = self::STATUS_OK,
        $contentType = self::MIME_APP_XML,
        $charset = "",
        $content = ""
    ) {
        parent::__construct($status, $contentType, $charset, $content);
    }
}
