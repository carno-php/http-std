<?php
/**
 * PSR helper
 * User: moyo
 * Date: 13/09/2017
 * Time: 5:14 PM
 */

namespace Carno\HTTP\Standard;

use Carno\HTTP\Standard\Streams\Body;
use Psr\Http\Message\MessageInterface as Message;
use Psr\Http\Message\StreamInterface as Stream;
use Psr\Http\Message\UriInterface as Uri;

trait Helper
{
    /**
     * @param Uri $uri
     * @return string
     */
    protected function getUriPath(Uri $uri) : string
    {
        $path = $uri->getPath();

        $uri->getQuery() && $path .= '?' . $uri->getQuery();
        $uri->getFragment() && $path .= '#' . $uri->getFragment();

        return $path;
    }

    /**
     * @param Message $message
     * @return array
     */
    protected function getHeaderLines(Message $message) : array
    {
        $lines = [];

        foreach ($message->getHeaders() as $key => $values) {
            $lines[$key] = implode(',', $values);
        }

        return $lines;
    }

    /**
     * @param string $data
     * @return Stream
     */
    protected function payloadToBody(string $data = null) : ?Stream
    {
        return is_null($data) ? null : new Body($data);
    }

    /**
     * @param array $query
     * @return string
     */
    protected function queryToString(array $query) : string
    {
        $q = '';

        array_walk($query, static function ($val, $key) use (&$q) {
            $q .= "{$key}={$val}&";
        });

        $q && $q = substr($q, 0, -1);

        return $q;
    }
}
