<?php
/**
 * HTTP form
 * User: moyo
 * Date: 2018/6/19
 * Time: 3:38 PM
 */

namespace Carno\HTTP\Standard\Streams;

use Carno\HTTP\Standard\Streams\Ops\Memory;
use Carno\HTTP\Standard\Streams\Ops\Printable;
use Psr\Http\Message\StreamInterface;

class Form implements StreamInterface
{
    use Memory, Printable;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var File[]
     */
    private $files = [];

    /**
     * Form constructor.
     * @param array $elements
     */
    public function __construct(array $elements)
    {
        foreach ($elements as $name => $element) {
            if (is_object($element) && $element instanceof File) {
                $this->files[$name] = $element;
            } else {
                $this->data[$name] = (string) $element;
            }
        }
    }

    /**
     * @return array
     */
    public function data() : array
    {
        return $this->data;
    }

    /**
     * @return File[]
     */
    public function files() : array
    {
        return $this->files;
    }
}
