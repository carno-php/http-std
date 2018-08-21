<?php
/**
 * Printable means has "__toString"
 * User: moyo
 * Date: 2018/6/19
 * Time: 4:36 PM
 */

namespace Carno\HTTP\Standard\Streams\Ops;

trait Printable
{
    /**
     * @return string
     */
    public function __toString() : string
    {
        $this->rewind();
        return $this->getContents();
    }
}
