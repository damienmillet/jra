<?php

/**
 * Http Message file for defining the Message class.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core\Http\Message;

use Core\Http\Message\MessageInterface;

/**
 * Class Message
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class Message implements MessageInterface
{
    public function getProtocolVersion(): void
    {
        // Implement getProtocolVersion() method.
    }


    public function withProtocolVersion($version): void
    {
        // Implement withProtocolVersion() method.
    }


    public function getHeaders(): void
    {
        // Implement getHeaders() method.
    }


    public function hasHeader($name): void
    {
        // Implement hasHeader() method.
    }


    public function getHeader($name): void
    {
        // Implement getHeader() method.
    }


    public function getHeaderLine($name): void
    {
        // Implement getHeaderLine() method.
    }


    public function withHeader($name, $value)
    {
        // Implement withHeader() method.
    }


    public function withAddedHeader($name, $value): void
    {
        // Implement withAddedHeader() method.
    }


    public function withoutHeader($name): void
    {
        // Implement withoutHeader() method.
    }


    public function getBody(): void
    {
        // Implement getBody() method.
    }


    public function withBody(StreamInterface $body): void
    {
        // Implement withBody() method.
    }
}
