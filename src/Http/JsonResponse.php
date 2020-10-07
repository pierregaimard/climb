<?php

namespace Framework3\Http;

class JsonResponse extends Response
{
    /**
     * @param array $status
     * @param string $contentType
     * @param string $charset
     * @param string $content
     */
    public function __construct(
        $status = self::STATUS_OK,
        $contentType = self::MIME_APP_JSON,
        $charset = "",
        $content = ""
    ) {
        parent::__construct($status, $contentType, $charset, $content);
    }

    /**
     * @param $content
     */
    public function setContent($content): void
    {
        parent::setContent(json_encode($content));
    }
}
