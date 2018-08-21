<?php
/**
 * HTTP Standard Request
 * User: moyo
 * Date: 25/08/2017
 * Time: 12:26 PM
 */

namespace Carno\HTTP\Standard;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    /**
     * @var mixed
     */
    private $requestTarget = null;

    /**
     * @var string
     */
    private $method = 'GET';

    /**
     * @var UriInterface
     */
    private $uri = null;

    /**
     * Request constructor.
     * @param string $method
     * @param UriInterface $uri
     * @param array $headers
     * @param StreamInterface $body
     */
    public function __construct(
        string $method = 'GET',
        UriInterface $uri = null,
        array $headers = [],
        StreamInterface $body = null
    ) {
        parent::__construct($headers, $body);

        if ($method) {
            $this->withMethod($method);
        }

        if ($uri) {
            $this->withUri($uri, true);
        }
    }

    /**
     * @return string
     */
    public function getRequestTarget() : string
    {
        return $this->requestTarget;
    }

    /**
     * @param mixed $requestTarget
     * @return RequestInterface
     */
    public function withRequestTarget($requestTarget) : RequestInterface
    {
        $this->requestTarget = $requestTarget;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return RequestInterface
     */
    public function withMethod($method) : RequestInterface
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return UriInterface
     */
    public function getUri() : UriInterface
    {
        return $this->uri;
    }

    /**
     * @param UriInterface $uri
     * @param bool $preserveHost
     * @return RequestInterface
     */
    public function withUri(UriInterface $uri, $preserveHost = false) : RequestInterface
    {
        $this->uri = $uri;

        if (!$preserveHost) {
            empty($this->uri->getHost()) || $this->withHeader('Host', $this->uri->getHost());
        } else {
            if (!$this->hasHeader('Host')) {
                if (!empty($this->uri->getHost())) {
                    $this->withHeader('Host', $this->uri->getHost());
                }
            }
        }

        return $this;
    }
}
