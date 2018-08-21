<?php
/**
 * HTTP body
 * User: moyo
 * Date: 09/09/2017
 * Time: 2:47 AM
 */

namespace Carno\HTTP\Standard\Streams;

use Carno\HTTP\Standard\Streams\Ops\Memory;
use Carno\HTTP\Standard\Streams\Ops\Printable;
use Psr\Http\Message\StreamInterface;

class Body implements StreamInterface
{
    use Memory, Printable;

    /**
     * Body constructor.
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->write($data);
    }
}
