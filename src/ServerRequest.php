<?php
/**
 * HTTP Standard ServerRequest
 * User: moyo
 * Date: 29/09/2017
 * Time: 10:15 AM
 */

namespace Carno\HTTP\Standard;

use Carno\HTTP\Exception\IncompleteStandardException;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
    use Helper;

    /**
     * @var array
     */
    private $serverParams = [];

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var array
     */
    private $clientCookies = [];

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var array
     */
    private $posting = [];

    /**
     * ServerRequest constructor.
     * @param array $server
     * @param array $cookies
     * @param array $query
     * @param string $method
     * @param array $headers
     * @param string $payload
     */
    public function __construct(
        array $server = [],
        array $cookies = [],
        array $query = [],
        string $method = 'GET',
        array $headers = [],
        string $payload = null
    ) {
        parent::__construct($method, null, $headers, $this->payloadToBody($payload));

        $this->serverParams = $server;
        $this->withCookieParams($cookies);
        $this->withQueryParams($query);
    }

    /**
     * @return array
     */
    public function getServerParams() : array
    {
        return $this->serverParams;
    }

    /**
     * @return array
     */
    public function getCookieParams() : array
    {
        return $this->clientCookies;
    }

    /**
     * @param array $cookies
     * @return ServerRequestInterface
     */
    public function withCookieParams(array $cookies) : ServerRequestInterface
    {
        $this->clientCookies = $cookies;
        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParams() : array
    {
        return $this->queryParams;
    }

    /**
     * @param array $query
     * @return ServerRequestInterface
     */
    public function withQueryParams(array $query) : ServerRequestInterface
    {
        $this->queryParams = $query;
        return $this;
    }

    /**
     * @return array
     */
    public function getUploadedFiles() : array
    {
        return [];
    }

    /**
     * @param array $uploadedFiles
     * @return ServerRequestInterface
     */
    public function withUploadedFiles(array $uploadedFiles) : ServerRequestInterface
    {
        throw new IncompleteStandardException;
    }

    /**
     * @return array
     */
    public function getParsedBody()
    {
        return $this->posting;
    }

    /**
     * @param array $data
     * @return ServerRequestInterface
     */
    public function withParsedBody($data) : ServerRequestInterface
    {
        $this->posting = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return ServerRequestInterface
     */
    public function withAttribute($name, $value) : ServerRequestInterface
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return ServerRequestInterface
     */
    public function withoutAttribute($name) : ServerRequestInterface
    {
        unset($this->attributes[$name]);
        return $this;
    }
}
