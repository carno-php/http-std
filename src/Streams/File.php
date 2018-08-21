<?php
/**
 * File in "form"
 * User: moyo
 * Date: 2018/6/19
 * Time: 3:41 PM
 */

namespace Carno\HTTP\Standard\Streams;

class File
{
    /**
     * @var string
     */
    private $path = null;

    /**
     * File constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function path() : string
    {
        return $this->path;
    }
}
