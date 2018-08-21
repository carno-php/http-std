<?php
/**
 * HTTP Standard Message
 * User: moyo
 * Date: 25/08/2017
 * Time: 10:56 AM
 */

namespace Carno\HTTP\Standard;

use Carno\HTTP\Standard\Streams\Body;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use InvalidArgumentException;

class Message implements MessageInterface
{
    /**
     * @var string
     */
    private $version = '1.1';

    /**
     * @var StreamInterface
     */
    private $streaming = null;

    /**
     * @var array
     */
    private $headersO = [];

    /**
     * @var array
     */
    private $headersL = [];

    /**
     * Message constructor.
     * @param array $headers
     * @param StreamInterface $body
     * @param string $protocol
     */
    public function __construct(
        array $headers = [],
        StreamInterface $body = null,
        string $protocol = null
    ) {
        foreach ($headers as $name => $value) {
            $this->withHeader($name, $value);
        }

        $body && $this->streaming = $body;

        $protocol && $this->withProtocolVersion($protocol);
    }

    /**
     * @return string
     */
    public function getProtocolVersion() : string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return MessageInterface
     */
    public function withProtocolVersion($version) : MessageInterface
    {
        if ($version === '1.0' || $version === '1.1') {
            $this->version = $version;
        }
        return $this;
    }

    /**
     * @return string[][]
     */
    public function getHeaders() : array
    {
        return $this->headersO;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader($name) : bool
    {
        return isset($this->headersL[strtolower($name)]);
    }

    /**
     * @param string $name
     * @return array
     */
    public function getHeader($name) : array
    {
        return $this->headersO[$this->headersL[strtolower($name)] ?? ''] ?? [];
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeaderLine($name) : string
    {
        return implode(',', $this->getHeader($name));
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return MessageInterface
     */
    public function withHeader($name, $value) : MessageInterface
    {
        $this->headersO[$this->parseHeaderName($name)] = $this->parseHeadersVal($value);
        return $this;
    }

    /**
     * @param string $name
     * @return MessageInterface
     */
    public function withoutHeader($name) : MessageInterface
    {
        if ($this->hasHeader($name)) {
            $nameL = strtolower($name);
            $nameO = $this->parseHeaderName($name);
            unset($this->headersL[$nameL]);
            unset($this->headersO[$nameO]);
        }
        return $this;
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return MessageInterface
     */
    public function withAddedHeader($name, $value) : MessageInterface
    {
        $this->headersO[$this->parseHeaderName($name)] = array_merge(
            $this->headersO[$name],
            $this->parseHeadersVal($value)
        );
        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    private function parseHeaderName(string $name) : string
    {
        $nameO = $name;
        $nameL = strtolower($name);

        isset($this->headersL[$nameL])
            ? $nameO = $this->headersL[$nameL]
            : $this->headersL[$nameL] = $nameO
        ;

        return $nameO;
    }

    /**
     * @param $value
     * @return array
     */
    private function parseHeadersVal($value) : array
    {
        $headers = is_array($value) ? $value : (is_scalar($value) ? [$value] : null);

        if (is_null($headers)) {
            throw new InvalidArgumentException('HTTP header not acceptable');
        }

        return $headers;
    }

    /**
     * @return StreamInterface
     */
    public function getBody() : StreamInterface
    {
        return $this->streaming ?? new Body('');
    }

    /**
     * @param StreamInterface $body
     * @return MessageInterface|static
     */
    public function withBody(StreamInterface $body) : MessageInterface
    {
        $new = clone $this;
        $new->streaming = $body;
        return $new;
    }
}
