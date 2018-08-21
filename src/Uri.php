<?php
/**
 * HTTP Standard Uri
 * User: moyo
 * Date: 25/08/2017
 * Time: 12:52 PM
 */

namespace Carno\HTTP\Standard;

use Carno\HTTP\Standard\Utils\SchemePorts;
use Carno\HTTP\Standard\Utils\UriEncoder;
use Psr\Http\Message\UriInterface;
use InvalidArgumentException;

class Uri implements UriInterface
{
    use Helper;

    /**
     * @var string
     */
    private $scheme = '';

    /**
     * @var string
     */
    private $authority = '';

    /**
     * @var string
     */
    private $userInfo = '';

    /**
     * @var string
     */
    private $host = '';

    /**
     * @var int
     */
    private $port = null;

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $query = '';

    /**
     * @var string
     */
    private $fragment = '';

    /**
     * Uri constructor.
     * @param string $scheme
     * @param string $host
     * @param int $port
     * @param string $path
     * @param string|array $query
     * @param string $fragment
     */
    public function __construct(
        string $scheme = 'http',
        string $host = 'localhost',
        int $port = null,
        string $path = '/',
        $query = null,
        string $fragment = null
    ) {
        if (is_array($query)) {
            $query = $this->queryToString($query);
        }

        $this->assignScheme($this, $scheme);
        $this->assignHost($this, $host);
        $this->assignPort($this, $port);
        $path && $this->assignPath($this, $path);
        $query && $this->assignQuery($this, $query);
        $fragment && $this->assignFragment($this, $fragment);
    }

    /**
     * @return string
     */
    public function getScheme() : string
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getAuthority() : string
    {
        return $this->authority;
    }

    /**
     * @return string
     */
    public function getUserInfo() : string
    {
        return $this->userInfo;
    }

    /**
     * @return string
     */
    public function getHost() : string
    {
        return $this->host;
    }

    /**
     * @return int|null
     */
    public function getPort() : ?int
    {
        if ($this->port) {
            return SchemePorts::defined($this->scheme, $this->port) ? null : $this->port;
        } elseif ($this->scheme) {
            return SchemePorts::resolve($this->scheme);
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getQuery() : string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getFragment() : string
    {
        return $this->fragment;
    }

    /**
     * @param string $scheme
     * @return UriInterface
     */
    public function withScheme($scheme) : UriInterface
    {
        $this->assignScheme($new = clone $this, $scheme);
        return $new;
    }

    /**
     * @param Uri $uri
     * @param string $scheme
     */
    private function assignScheme(Uri $uri, string $scheme) : void
    {
        $uri->scheme = $scheme;
    }

    /**
     * @param string $user
     * @param string|null $password
     * @return UriInterface
     */
    public function withUserInfo($user, $password = null) : UriInterface
    {
        $new = clone $this;
        $new->userInfo = $user . (is_null($password) ? '' : ":{$password}");
        return $new;
    }

    /**
     * @param string $host
     * @return UriInterface
     */
    public function withHost($host) : UriInterface
    {
        $this->assignHost($new = clone $this, $host);
        return $new;
    }

    /**
     * @param Uri $uri
     * @param string $host
     */
    private function assignHost(Uri $uri, string $host) : void
    {
        $uri->host = strtolower($host);
    }

    /**
     * @param int|null $port
     * @return UriInterface
     */
    public function withPort($port) : UriInterface
    {
        $this->assignPort($new = clone $this, $port);
        return $new;
    }

    /**
     * @param Uri $uri
     * @param int|null $port
     */
    private function assignPort(Uri $uri, ?int $port) : void
    {
        if (is_numeric($port) && ($port < 0 || $port > 0xFFFF)) {
            throw new InvalidArgumentException('Invalid port range [0-65535]');
        } else {
            $uri->port = $port;
        }
    }

    /**
     * @param string $path
     * @return UriInterface
     */
    public function withPath($path) : UriInterface
    {
        $this->assignPath($new = clone $this, $path);
        return $new;
    }

    /**
     * @param Uri $uri
     * @param string $path
     */
    private function assignPath(Uri $uri, string $path) : void
    {
        $uri->path = UriEncoder::process(UriEncoder::TYPE_PATH, $path);
    }

    /**
     * @param string $query
     * @return UriInterface
     */
    public function withQuery($query) : UriInterface
    {
        $this->assignQuery($new = clone $this, $query);
        return $new;
    }

    /**
     * @param Uri $uri
     * @param string $query
     */
    private function assignQuery(Uri $uri, string $query) : void
    {
        $uri->query = UriEncoder::process(UriEncoder::TYPE_QUERY, $query);
    }

    /**
     * @param string $fragment
     * @return UriInterface
     */
    public function withFragment($fragment) : UriInterface
    {
        $this->assignFragment($new = clone $this, $fragment);
        return $new;
    }

    /**
     * @param Uri $uri
     * @param string $fragment
     */
    private function assignFragment(Uri $uri, string $fragment) : void
    {
        $uri->fragment = UriEncoder::process(UriEncoder::TYPE_QUERY, $fragment);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        $uri = '';

        $scheme = $this->getScheme();
        $scheme && $uri .= "{$scheme}:";

        $authority = $this->getAuthority();
        ($scheme || $authority) && $uri .= "//{$authority}";

        $uri .= $this->getHost();

        $port = $this->getPort();
        $port && $uri .= ":{$port}";

        $uri .= $this->getPath();

        $query = $this->getQuery();
        $query && $uri .= "?{$query}";

        $fragment = $this->getFragment();
        $fragment && $uri .= "#{$fragment}";

        return $uri;
    }
}
