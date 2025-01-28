<?php

/**
 * Http Message file for defining the Response class.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core\Http\Message;

use Core\Http\Message\Stream;
use Core\Http\Message\StatusCode;

/**
 * Class Response
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class Response implements ResponseInterface
{
    private StatusCode $_statusCode;

    private string $_reasonPhrase;

    private array $_headers = [];

    private StreamInterface $_body;

    private int $_protocolVersion = 1.1;


    /**
     * Get the status code.
     *
     * @return integer The status code.
     */
    public function getStatusCode(): StatusCode
    {
        return $this->_statusCode;
    }


    /**
     * Set the status code and reason phrase.
     *
     * @param integer $code         The status code.
     * @param string  $reasonPhrase The reason phrase.
     *
     * @return self
     */
    public function withStatus($code, $reasonPhrase = ''): static
    {
        $this->_statusCode   = StatusCode::from($code);
        $this->_reasonPhrase = $reasonPhrase;
        return $this;
    }


    /**
     * Get the reason phrase.
     *
     * @return string The reason phrase.
     */
    public function getReasonPhrase(): string
    {
        return $this->_reasonPhrase;
    }


    /**
     * Get the protocol version.
     *
     * @return integer The protocol version.
     */
    public function getProtocolVersion(): int
    {
        return $this->_protocolVersion;
    }


    /**
     * Set the protocol version.
     *
     * @param integer $version The protocol version.
     *
     * @return self
     */
    public function withProtocolVersion($version): self
    {
        $this->_protocolVersion = (int) $version;
        return $this;
    }


    /**
     * Get all headers.
     *
     * @return array The headers.
     */
    public function getHeaders(): array
    {
        return $this->_headers;
    }


    /**
     * Check if a header exists.
     *
     * @param string $name The header name.
     *
     * @return boolean True if the header exists, false otherwise.
     */
    public function hasHeader($name): bool
    {
        return isset($this->_headers[$name]);
    }


    /**
     * Get a header by name.
     *
     * @param string $name The header name.
     *
     * @return array The header values.
     */
    public function getHeader($name): array
    {
        return ($this->_headers[$name] ?? []);
    }


    /**
     * Get a header line by name.
     *
     * @param string $name The header name.
     *
     * @return string The header line.
     */
    public function getHeaderLine($name): string
    {
        return implode(', ', ($this->_headers[$name] ?? []));
    }


    /**
     * Set a header.
     *
     * @param string       $name  The header name.
     * @param string|array $value The header value.
     *
     * @return self
     */
    public function withHeader($name, $value): self
    {
        $this->_headers[$name] = $value;
        return $this;
    }


    /**
     * Add a header.
     *
     * @param string       $name  The header name.
     * @param string|array $value The header value.
     *
     * @return self
     */
    public function withAddedHeader($name, $value): self
    {
        $this->_headers[$name] = $value;
        return $this;
    }


    /**
     * Remove a header.
     *
     * @param string $name The header name.
     *
     * @return self
     */
    public function withoutHeader($name): self
    {
        $this->_headers[$name] = [];
        return $this;
    }


    /**
     * Get the body.
     *
     * @return StreamInterface The body.
     */
    public function getBody(): StreamInterface
    {
        return $this->_body;
    }


    /**
     * Set the body.
     *
     * @param StreamInterface $body The body.
     *
     * @return self
     */
    public function withBody(StreamInterface $body): self
    {
        $this->_body = $body;
        return $this;
    }


    /**
     * Set the body with JSON content and add the appropriate header.
     *
     * @param string $json The JSON content.
     *
     * @return self
     */
    public function withJson(string $json): self
    {
        $this->withAddedHeader('Content-Type', 'application/json');
        $stream = new Stream();
        $stream->write($json);
        $this->withBody($stream);
        return $this;
    }
}
