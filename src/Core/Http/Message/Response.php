<?php

namespace Core\Http\Message;

use Core\Http\Message\Stream;
use Core\Http\Message\StatusCode;

/**
 * Class Response
 */
class Response implements ResponseInterface
{
    private StatusCode $statusCode;

    private string $reasonPhrase;

    private array $headers = [];

    private StreamInterface $body;

    private int $protocolVersion = 1.1;


    /**
     * Get the status code.
     *
     * @return integer The status code.
     */
    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
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
        $this->statusCode   = StatusCode::from($code);
        $this->reasonPhrase = $reasonPhrase;
        return $this;
    }


    /**
     * Get the reason phrase.
     *
     * @return string The reason phrase.
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }


    /**
     * Get the protocol version.
     *
     * @return integer The protocol version.
     */
    public function getProtocolVersion(): int
    {
        return $this->protocolVersion;
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
        $this->protocolVersion = (int) $version;
        return $this;
    }


    /**
     * Get all headers.
     *
     * @return array The headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
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
        return isset($this->headers[$name]);
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
        return ($this->headers[$name] ?? []);
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
        return implode(', ', ($this->headers[$name] ?? []));
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
        $this->headers[$name] = $value;
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
        $this->headers[$name] = $value;
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
        $this->headers[$name] = [];
        return $this;
    }


    /**
     * Get the body.
     *
     * @return StreamInterface The body.
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
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
        $this->body = $body;
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
