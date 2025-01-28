<?php

/**
 * Http Message file for defining the StreamFactory Interface.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core\Http\Message;

use Core\Http\Message\StreamInterface;

/**
 * Interface StreamFactoryInterface
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
interface StreamFactoryInterface
{
    /**
     * Create a new stream from a string.
     *
     * @param string $content The content to include in the stream.
     *
     * @return StreamInterface
     */
    public function createStream(string $content = ''): StreamInterface;


    /**
     * Create a new stream from a file.
     *
     * @param string $filename The filename to read from.
     * @param string $mode     The mode in which to open the file.
     *
     * @return StreamInterface
     */
    public function createStreamFromFile(
        string $filename,
        string $mode = 'r'
    ): StreamInterface;


    /**
     * Create a new stream from an existing resource.
     *
     * @param resource $resource The resource to use as the basis for the stream.
     *
     * @return StreamInterface
     */
    public function createStreamFromResource($resource): StreamInterface;
}
