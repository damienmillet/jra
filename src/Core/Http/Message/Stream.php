<?php

/**
 * Http Message file for defining the Stream class.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core\Http\Message;

/**
 * Class Stream
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class Stream implements StreamInterface
{
    private $_stream;

    private $_meta;

    private $_readable;

    private $_writable;

    private $_seekable;


    /**
     * Stream constructor.
     *
     * @param string|resource $stream The stream to wrap
     *
     * @throws \InvalidArgumentException If the stream is not valid
     */
    public function __construct($stream)
    {
        if (is_string($stream)) {
            $this->_stream = fopen($stream, 'r+');
        } elseif (is_resource($stream)) {
            $this->_stream = $stream;
        } else {
            throw new \InvalidArgumentException('Invalid stream provided');
        }

        $this->_meta     = stream_get_meta_data($this->_stream);
        $this->_readable = isset($this->_meta['mode']) && (strstr($this->_meta['mode'], 'r') || strstr($this->_meta['mode'], '+'));
        $this->_writable = isset($this->_meta['mode']) && (strstr($this->_meta['mode'], 'w') || strstr($this->_meta['mode'], '+'));
        $this->_seekable = $this->_meta['seekable'];
    }


    /**
     * Converts the stream to a string.
     *
     * @return string The contents of the stream
     */
    public function __toString()
    {
        if (!$this->_stream) {
            return '';
        }

        $this->rewind();
        return stream_get_contents($this->_stream);
    }


    /**
     * Closes the stream.
     *
     * @return void
     */
    public function close()
    {
        if ($this->_stream) {
            fclose($this->_stream);
            $this->_stream = null;
        }
    }


    /**
     * Detaches the stream from the object.
     *
     * @return resource|null The detached stream resource
     */
    public function detach()
    {
        $result        = $this->_stream;
        $this->_stream = null;
        return $result;
    }


    /**
     * Gets the size of the stream.
     *
     * @return integer|null The size of the stream in bytes, or null if unknown
     */
    public function getSize()
    {
        if (!$this->_stream) {
            return null;
        }

        $stats = fstat($this->_stream);
        return $stats['size'] ?? null;
    }


    /**
     * Gets the current position of the stream.
     *
     * @return integer The current position of the stream
     * @throws \RuntimeException If the stream is not available
     */
    public function tell()
    {
        if (!$this->_stream) {
            throw new \RuntimeException('Stream is not available');
        }

        $result = ftell($this->_stream);
        if ($result === false) {
            throw new \RuntimeException('Unable to determine stream position');
        }

        return $result;
    }


    /**
     * Checks if the stream is at the end.
     *
     * @return boolean True if the stream is at the end, false otherwise
     */
    public function eof()
    {
        return $this->_stream ? feof($this->_stream) : true;
    }


    /**
     * Checks if the stream is seekable.
     *
     * @return boolean True if the stream is seekable, false otherwise
     */
    public function isSeekable()
    {
        return $this->_seekable;
    }


    /**
     * Seeks to a position in the stream.
     *
     * @param integer $offset The position to seek to
     * @param integer $whence The seek mode (SEEK_SET, SEEK_CUR, SEEK_END)
     *
     * @return void
     * @throws \RuntimeException If the stream is not seekable
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->_seekable) {
            throw new \RuntimeException('Stream is not seekable');
        }

        if (fseek($this->_stream, $offset, $whence) === -1) {
            throw new \RuntimeException('Unable to seek in stream');
        }
    }


    /**
     * Rewinds the stream to the beginning.
     *
     * @return void
     */
    public function rewind()
    {
        $this->seek(0);
    }


    /**
     * Checks if the stream is writable.
     *
     * @return boolean True if the stream is writable, false otherwise
     */
    public function isWritable(): bool
    {
        return $this->_writable;
    }


    /**
     * Writes data to the stream.
     *
     * @param string $string The data to write
     *
     * @return void
     * @throws \RuntimeException If the stream is not writable
     */
    public function write($string)
    {
        if (!$this->_writable) {
            throw new \RuntimeException('Stream is not writable');
        }

        fwrite($this->_stream, $string);
    }


    /**
     * Checks if the stream is readable.
     *
     * @return boolean True if the stream is readable, false otherwise
     */
    public function isReadable(): bool
    {
        return $this->_readable;
    }


    /**
     * Reads data from the stream.
     *
     * @param integer $length The number of bytes to read
     *
     * @return string The data read from the stream
     * @throws \RuntimeException If the stream is not readable
     */
    public function read($length)
    {
        if (!$this->_readable) {
            throw new \RuntimeException('Stream is not readable');
        }

        return fread($this->_stream, $length);
    }


    /**
     * Gets the remaining contents of the stream.
     *
     * @return string The remaining contents of the stream
     * @throws \RuntimeException If the stream is not available
     */
    public function getContents()
    {
        if (!$this->_stream) {
            throw new \RuntimeException('Stream is not available');
        }

        return stream_get_contents($this->_stream);
    }


    /**
     * Gets the metadata of the stream.
     *
     * @param string|null $key The specific metadata key to retrieve,
     *                         or null to retrieve all metadata
     *
     * @return mixed The metadata value, or null if the key does not exist
     */
    public function getMetadata($key = null)
    {
        if (!$this->_stream) {
            return null;
        }

        $meta = stream_get_meta_data($this->_stream);
        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }


    /**
     * Gets the underlying stream resource.
     *
     * @return resource|null The stream resource
     */
    public function getStream()
    {
        return $this->_stream;
    }


    /**
     * Sets the underlying stream resource.
     *
     * @param resource|null $stream The stream resource
     *
     * @return void
     */
    public function setStream($stream)
    {
        $this->_stream = $stream;
    }


    /**
     * Gets the metadata of the stream.
     *
     * @return array|null The metadata of the stream
     */
    public function getMeta()
    {
        return $this->_meta;
    }


    /**
     * Sets the metadata of the stream.
     *
     * @param array|null $meta The metadata of the stream
     *
     * @return void
     */
    public function setMeta($meta)
    {
        $this->_meta = $meta;
    }


    /**
     * Checks if the stream is readable.
     *
     * @return boolean True if the stream is readable, false otherwise
     */
    private function _getReadable()
    {
        return $this->_readable;
    }


    /**
     * Sets the readability of the stream.
     *
     * @param boolean $readable True if the stream is readable, false otherwise
     *
     * @return void
     */
    public function setReadable($readable)
    {
        $this->_readable = $readable;
    }


    /**
     * Checks if the stream is writable.
     *
     * @return boolean True if the stream is writable, false otherwise
     */
    public function getWritable()
    {
        return $this->_writable;
    }


    /**
     * Sets the writability of the stream.
     *
     * @param boolean $writable True if the stream is writable, false otherwise
     *
     * @return void
     */
    public function setWritable($writable)
    {
        $this->_writable = $writable;
    }


    /**
     * Checks if the stream is seekable.
     *
     * @return boolean True if the stream is seekable, false otherwise
     */
    public function getSeekable()
    {
        return $this->_seekable;
    }


    /**
     * Sets the seekability of the stream.
     *
     * @param boolean $seekable True if the stream is seekable, false otherwise
     *
     * @return void
     */
    public function setSeekable($seekable)
    {
        $this->_seekable = $seekable;
    }
}
