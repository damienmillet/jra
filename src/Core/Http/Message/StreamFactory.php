<?php

namespace Core\Http\Message;

use Core\Http\Message\Stream;
use Core\Http\Message\StreamInterface;

/**
 * Class StreamFactory
 * Factory for creating Stream instances.
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     https://damien-millet.dev
 */
class StreamFactory implements StreamFactoryInterface
{
    /**
     * Create a Stream instance from a string.
     *
     * @param string $content The content to be converted to a Stream.
     *
     * @return StreamInterface The created Stream instance.
     */
    public function createStream(string $content = ''): StreamInterface
    {
        return $this->createFromResource($this->_createMemoryStream($content));
    }


    /**
     * Create a Stream instance from a file.
     *
     * @param string $filename The file path to read.
     * @param string $mode     The mode in which to open the file.
     *
     * @return StreamInterface The created Stream instance.
     * @throws \RuntimeException If the file cannot be read.
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new \RuntimeException("File '{$filename}' is not readable or does not exist.");
        }

        return $this->createFromResource(fopen($filename, $mode));
    }


    /**
     * Create a Stream instance from a resource.
     *
     * @param resource $resource A valid PHP resource.
     *
     * @return StreamInterface The created Stream instance.
     * @throws \InvalidArgumentException If the provided resource is invalid.
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException('Provided value is not a valid resource.');
        }

        return new Stream($resource);
    }


    /**
     * Create a Stream instance from a JSON array.
     *
     * @param array $data The data to encode as JSON.
     *
     * @return StreamInterface The created Stream instance.
     * @throws \JsonException If JSON encoding fails.
     */
    public function createStreamFromJson(array $data): StreamInterface
    {
        $json = json_encode($data, JSON_THROW_ON_ERROR);
        return $this->createStream($json);
    }


    /**
     * Create a Stream instance from a text string.
     *
     * @param string $text The text content to convert to a Stream.
     *
     * @return StreamInterface The created Stream instance.
     */
    public function createStreamFromText(string $text): StreamInterface
    {
        return $this->createStream($text);
    }


    /**
     * Create a temporary in-memory stream and write content to it.
     *
     * @param string $content The content to write to the stream.
     *
     * @return resource The created in-memory stream resource.
     */
    private function _createMemoryStream(string $content)
    {
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, $content);
        rewind($resource);

        return $resource;
    }


    /**
     * Create a Stream instance from a resource.
     *
     * @param resource $resource The resource to be converted to a Stream.
     *
     * @return StreamInterface The created Stream instance.
     */
    public static function createFromResource($resource): Stream
    {
        return new Stream($resource);
    }
}
