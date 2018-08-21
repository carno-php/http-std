<?php
/**
 * Memory buffered
 * User: moyo
 * Date: 12/09/2017
 * Time: 2:20 PM
 */

namespace Carno\HTTP\Standard\Streams\Ops;

trait Memory
{
    /**
     * @var int
     */
    private $size = 0;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var string
     */
    private $buffer = '';

    /**
     * @var bool
     */
    private $closed = false;

    /**
     * @return void
     */
    public function close() : void
    {
        $this->closed = true;
    }

    /**
     * @return null
     */
    public function detach()
    {
        $this->close();
        return null;
    }

    /**
     * @return int
     */
    public function getSize() : int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function tell() : int
    {
        return $this->offset;
    }

    /**
     * @return bool
     */
    public function eof() : bool
    {
        return $this->offset >= $this->size;
    }

    /**
     * @return bool
     */
    public function isSeekable() : bool
    {
        return true;
    }

    /**
     * @param int $offset
     * @param int $whence
     */
    public function seek($offset, $whence = SEEK_SET) : void
    {
        switch ($whence) {
            case SEEK_SET:
                $this->offset = $offset;
                break;
            case SEEK_CUR:
                $this->offset += $offset;
                break;
            case SEEK_END:
                $this->offset = $this->size - $offset;
                break;
        }
    }

    /**
     */
    public function rewind() : void
    {
        $this->seek(0);
    }

    /**
     * @return bool
     */
    public function isWritable() : bool
    {
        return true;
    }

    /**
     * @param string $string
     * @return int
     */
    public function write($string) : int
    {
        $this->buffer .= $string;
        $this->size += ($length = strlen($string));
        return $length;
    }

    /**
     * @return bool
     */
    public function isReadable() : bool
    {
        return true;
    }

    /**
     * @param int $length
     * @return string
     */
    public function read($length) : string
    {
        $data = substr($this->buffer, $this->offset, $length);
        $this->offset += $length;
        return $data;
    }

    /**
     * @return string
     */
    public function getContents() : string
    {
        return $this->read($this->size - $this->offset);
    }

    /**
     * @param string $key
     * @return null
     */
    public function getMetadata($key = null)
    {
        return null;
    }
}
