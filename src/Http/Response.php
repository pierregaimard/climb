<?php

namespace Framework3\Http;

use Framework3\Bag\Bag;
use Framework3\Http\Session\Session;
use Framework3\Http\Session\SessionInterface;

class Response
{
    public const STATUS_OK =            ['code' => 200, 'name' => '200 OK'];
    public const STATUS_NO_CONTENT =    ['code' => 204, 'name' => '204 No Content'];
    public const STATUS_BAD_REQUEST =   ['code' => 400, 'name' => '400 Bad Request'];
    public const STATUS_UNAUTHORIZED =  ['code' => 401, 'name' => '401 Unauthorized'];
    public const STATUS_FORBIDDEN =     ['code' => 403, 'name' => '403 Forbidden'];
    public const STATUS_NOT_FOUND =     ['code' => 404, 'name' => '404 Not Found'];

    public const MIME_TEXT_PAIN =       "text/plain";
    public const MIME_TEXT_HTML =       "text/html";
    public const MIME_TEXT_JAVASCRIPT = "text/javascript";
    public const MIME_TEXT_CALENDAR =   "text/calendar";
    public const MIME_TEXT_CSV =        "text/csv";
    public const MIME_APP_JSON =        "application/json";
    public const MIME_APP_XML =         "application/xml";
    public const MIME_APP_PDF =         "application/pdf";
    public const MIME_APP_ZIP =         "application/zip";
    public const CHARSET_UTF8 =         "UTF-8";

    /**
     * @var string
     */
    private string $statusName;

    /**
     * @var int
     */
    private int $statusCode;

    /**
     * @var string
     */
    private string $contentType;

    /**
     * @var string
     */
    private string $charset;

    /**
     * Response body.
     *
     * @var string|null
     */
    private ?string $content;

    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * @param array         $status
     * @param string        $contentType
     * @param string        $charset
     * @param string        $content
     */
    public function __construct(
        array $status = self::STATUS_OK,
        string $contentType = self::MIME_TEXT_HTML,
        string $charset = self::CHARSET_UTF8,
        string $content = ""
    ) {
        $this->setStatus($status);
        $this->contentType = $contentType;
        $this->charset     = $charset;
        $this->content     = $content;
        $this->session     = new Session();
    }

    /**
     * @return string
     */
    public function getStatusName(): string
    {
        return $this->statusName;
    }

    /**
     * @param string $statusName
     */
    public function setStatusName(string $statusName): void
    {
        $this->statusName = $statusName;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param array $status
     */
    public function setStatus(array $status): void
    {
        $this->statusCode = $status['code'];
        $this->statusName = $status['name'];
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     */
    public function setCharset(string $charset): void
    {
        $this->charset = $charset;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param mixed|null $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return Bag
     */
    public function getData(): Bag
    {
        return $this->session->getRequestData();
    }

    /**
     * @return Bag
     */
    public function getFlashes(): Bag
    {
        return $this->session->getFlashes();
    }

    /**
     * Content-type is always return
     * Default: text/html; charset=UTF-8
     */
    protected function setContentTypeHeader()
    {
        $charset = (substr($this->contentType, 0, 4) === "text") ? "; charset=$this->charset" : null;
        header("Content-Type: " . $this->contentType . $charset);
    }

    /**
     * Status is always return
     * Default: 200 OK
     */
    protected function setStatusHeader(): void
    {
        header("Status: $this->statusName", false, $this->statusCode);
    }

    /**
     * set response data before sending the response.
     */
    protected function setSessionData(): void
    {
        $this->session->setRequestData();
        $this->session->setFlashes();
    }

    /**
     * Set http headers before sending the response
     */
    protected function setHeaders()
    {
        if (empty($this->content) and $this->statusCode === self::STATUS_OK["code"]) {
            $this->setStatus(self::STATUS_NO_CONTENT);
        }

        $this->setStatusHeader();
        $this->setContentTypeHeader();
    }

    /**
     * send response.
     */
    public function send(): void
    {
        $this->setSessionData();
        $this->setHeaders();
        #Content
        if ($this->content) {
            echo filter_var($this->getContent());
        }
    }
}
