<?php

use Core\Http\Message\UriInterface;

/**
 * Class Uri
 *
 * Implements the UriInterface to handle URI components.
 */
class Uri implements UriInterface
{
    private string $scheme;

    private string $host;

    private int $port;

    private string $path;

    private string $query;

    private string $fragment;


    public function __construct(string $scheme, string $host, int $port, string $path, string $query, string $fragment)
    {
        $this->scheme   = $scheme;
        $this->host     = $host;
        $this->port     = $port;
        $this->path     = $path;
        $this->query    = $query;
        $this->fragment = $fragment;
    }


    public function getScheme(): string
    {
        return $this->scheme;
    }


    public function getHost(): string
    {
        return $this->host;
    }


    public function getPort(): int
    {
        return $this->port;
    }


    public function getPath(): string
    {
        return $this->path;
    }


    public function getQuery(): string
    {
        return $this->query;
    }


    public function getAuthority(): string
    {
        $authority = $this->host;
        if ($this->port) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }


    public function getUserInfo(): string
    {
        // Assuming user info is not implemented in this example
        return '';
    }


    public function withUserInfo($user, $password = null): UriInterface
    {
        // Assuming user info is not implemented in this example
        return $this;
    }


    public function __toString(): string
    {
        return $this->scheme . '://' . $this->getAuthority() . $this->path . '?' . $this->query . '#' . $this->fragment;
    }


    public function withScheme($scheme): UriInterface
    {
        $new          = clone $this;
        $new->_scheme = $scheme;
        return $new;
    }


    public function withHost($host): UriInterface
    {
        $new        = clone $this;
        $new->_host = $host;
        return $new;
    }


    public function withPort($port): UriInterface
    {
        $new        = clone $this;
        $new->_port = $port;
        return $new;
    }


    public function withPath($path): UriInterface
    {
        $new        = clone $this;
        $new->_path = $path;
        return $new;
    }


    public function withQuery($query): UriInterface
    {
        $new         = clone $this;
        $new->_query = $query;
        return $new;
    }


    public function withFragment($fragment): UriInterface
    {
        $new            = clone $this;
        $new->_fragment = $fragment;
        return $new;
    }


    public function getFragment(): string
    {
        return $this->fragment;
    }
}
