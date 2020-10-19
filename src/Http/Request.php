<?php

namespace Climb\Http;

use Climb\Http\Bag\ServerBag;
use Climb\Http\Bag\GetBag;
use Climb\Http\Bag\PostBag;
use Climb\Http\Bag\CookieBag;
use Climb\Http\Bag\FilesBag;
use Climb\Bag\Bag;
use Climb\Http\Session\SessionInterface;

class Request
{
    public const METHOD_GET     = 'GET';
    public const METHOD_HEAD    = 'HEAD';
    public const METHOD_POST    = 'POST';
    public const METHOD_PUT     = 'PUT';
    public const METHOD_DELETE  = 'DELETE';
    public const METHOD_CONNECT = 'CONNECT';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE   = 'TRACE';
    public const METHOD_PATCH   = 'PATCH';

    /**
     * array of $_SERVER data.
     *
     * @var ServerBag
     */
    private ServerBag $server;

    /**
     * Request method
     *
     * @var string
     */
    private string $method;

    /**
     * Request URI without GET data if exists
     *
     * @var string
     */
    private string $path;

    /**
     * Bag of request uri variable data.
     *
     * e.g. /user/{id} & /user/13 => ['id' => 13]
     *
     * @var Bag
     */
    private Bag $pathData;

    /**
     * array of $_GET data
     *
     * @var GetBag
     */
    private GetBag $get;

    /**
     * array of $_POST data
     *
     * @var PostBag
     */
    private PostBag $post;

    /**
     * array of $_COOKIE data
     *
     * @var CookieBag
     */
    private CookieBag $cookie;

    /**
     * bag of $_FILE data
     *
     * @var FilesBag
     */
    private FilesBag $files;

    /**
     * Session
     *
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * @param array            $server
     * @param array|null       $get
     * @param array|null       $post
     * @param array|null       $cookie
     * @param array|null       $files
     * @param SessionInterface $session
     * @param string           $requestPath
     * @param string           $method
     */
    public function __construct(
        array $server,
        ?array $get,
        ?array $post,
        ?array $cookie,
        ?array $files,
        SessionInterface $session,
        string $requestPath,
        string $method
    ) {
        $this->setServer($server);
        $this->setGet($get);
        $this->setPost($post);
        $this->setCookie($cookie);
        $this->setFiles($files);
        $this->setSession($session);
        $this->setPath($requestPath);
        $this->setMethod($method);
    }

    /**
     * @return ServerBag
     */
    public function getServer(): ServerBag
    {
        return $this->server;
    }

    /**
     * @param array $server
     */
    public function setServer(array $server): void
    {
        $this->server = new ServerBag($server);
    }

    /**
     * @return GetBag
     */
    public function getGet(): GetBag
    {
        return $this->get;
    }

    /**
     * @param array|null $get
     */
    public function setGet(?array $get): void
    {
        $this->get = new GetBag($get);
    }

    /**
     * @return PostBag
     */
    public function getPost(): PostBag
    {
        return $this->post;
    }

    /**
     * @param array|null $post
     */
    public function setPost(?array $post): void
    {
        $this->post = new PostBag($post);
    }

    /**
     * @return CookieBag
     */
    public function getCookie(): CookieBag
    {
        return $this->cookie;
    }

    /**
     * @param array|null $cookie
     */
    public function setCookie(?array $cookie): void
    {
        $this->cookie = new CookieBag($cookie);
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @param $method
     * @return bool
     */
    public function isMethod($method): bool
    {
        return $this->getMethod() === $method;
    }

    /**
     * @return bool
     */
    public function isMethodGet(): bool
    {
        return $this->isMethod(self::METHOD_GET);
    }

    /**
     * @return bool
     */
    public function isMethodPost(): bool
    {
        return $this->isMethod(self::METHOD_POST);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return Bag
     */
    public function getPathData(): Bag
    {
        return $this->pathData;
    }

    /**
     * @param Bag $pathData
     */
    public function setPathData(Bag $pathData): void
    {
        $this->pathData = $pathData;
    }

    /**
     * @return FilesBag
     */
    public function getFiles(): FilesBag
    {
        return $this->files;
    }

    /**
     * @param array|null $files
     */
    public function setFiles(?array $files): void
    {
        $this->files = new FilesBag($files);
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }
}
