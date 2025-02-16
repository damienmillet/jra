<?php

namespace Core\Http\Message;

/**
 * Class Stream
 */
class Stream implements StreamInterface
{
    private $stream;

    private $meta;

    private $readable;

    private $writable;

    private $seekable;


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
            $this->stream = fopen($stream, 'r+');
        } elseif (is_resource($stream)) {
            $this->stream = $stream;
        } else {
            throw new \InvalidArgumentException('Invalid stream provided');
        }

        $this->meta     = stream_get_meta_data($this->stream);
        $this->readable = isset($this->meta['mode']) && (strstr($this->meta['mode'], 'r') || strstr($this->meta['mode'], '+'));
        $this->writable = isset($this->meta['mode']) && (strstr($this->meta['mode'], 'w') || strstr($this->meta['mode'], '+'));
        $this->seekable = $this->meta['seekable'];
    }


    /**
     * Converts the stream to a string.
     *
     * @return string The contents of the stream
     */
    public function __toString()
    {
        if (!$this->stream) {
            return '';
        }

        $this->rewind();
        return stream_get_contents($this->stream);
    }


    /**
     * Closes the stream.
     *
     * @return void
     */
    public function close()
    {
        if ($this->stream) {
            fclose($this->stream);
            $this->stream = null;
        }
    }


    /**
     * Detaches the stream from the object.
     *
     * @return resource|null The detached stream resource
     */
    public function detach()
    {
        $result       = $this->stream;
        $this->stream = null;
        return $result;
    }


    /**
     * Gets the size of the stream.
     *
     * @return integer|null The size of the stream in bytes, or null if unknown
     */
    public function getSize()
    {
        if (!$this->stream) {
            return null;
        }

        $stats = fstat($this->stream);
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
        if (!$this->stream) {
            throw new \RuntimeException('Stream is not available');
        }

        $result = ftell($this->stream);
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
        return $this->stream ? feof($this->stream) : true;
    }


    /**
     * Checks if the stream is seekable.
     *
     * @return boolean True if the stream is seekable, false otherwise
     */
    public function isSeekable()
    {
        return $this->seekable;
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
        if (!$this->seekable) {
            throw new \RuntimeException('Stream is not seekable');
        }

        if (fseek($this->stream, $offset, $whence) === -1) {
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
        return $this->writable;
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
        if (!$this->writable) {
            throw new \RuntimeException('Stream is not writable');
        }

        fwrite($this->stream, $string);
    }


    /**
     * Checks if the stream is readable.
     *
     * @return boolean True if the stream is readable, false otherwise
     */
    public function isReadable(): bool
    {
        return $this->readable;
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
        if (!$this->readable) {
            throw new \RuntimeException('Stream is not readable');
        }

        return fread($this->stream, $length);
    }


    /**
     * Gets the remaining contents of the stream.
     *
     * @return string The remaining contents of the stream
     * @throws \RuntimeException If the stream is not available
     */
    public function getContents()
    {
        if (!$this->stream) {
            throw new \RuntimeException('Stream is not available');
        }

        return stream_get_contents($this->stream);
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
        if (!$this->stream) {
            return null;
        }

        $meta = stream_get_meta_data($this->stream);
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
        return $this->stream;
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
        $this->stream = $stream;
    }


    /**
     * Gets the metadata of the stream.
     *
     * @return array|null The metadata of the stream
     */
    public function getMeta()
    {
        return $this->meta;
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
        $this->meta = $meta;
    }


    /**
     * Checks if the stream is readable.
     *
     * @return boolean True if the stream is readable, false otherwise
     */
    private function _getReadable()
    {
        return $this->readable;
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
        $this->readable = $readable;
    }


    /**
     * Checks if the stream is writable.
     *
     * @return boolean True if the stream is writable, false otherwise
     */
    public function getWritable()
    {
        return $this->writable;
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
        $this->writable = $writable;
    }


    /**
     * Checks if the stream is seekable.
     *
     * @return boolean True if the stream is seekable, false otherwise
     */
    public function getSeekable()
    {
        return $this->seekable;
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
        $this->seekable = $seekable;
    }
}
