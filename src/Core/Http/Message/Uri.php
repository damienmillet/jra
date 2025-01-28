<?php

use Core\Http\Message\UriInterface;

/**
 * Class Uri
 *
 * Implements the UriInterface to handle URI components.
 */
class Uri implements UriInterface
{
    private string $_scheme;

    private string $_host;

    private int $_port;

    private string $_path;

    private string $_query;

    private string $_fragment;


    public function __construct(string $scheme, string $host, int $port, string $path, string $query, string $fragment)
    {
        $this->_scheme   = $scheme;
        $this->_host     = $host;
        $this->_port     = $port;
        $this->_path     = $path;
        $this->_query    = $query;
        $this->_fragment = $fragment;
    }


    public function getScheme(): string
    {
        return $this->_scheme;
    }


    public function getHost(): string
    {
        return $this->_host;
    }


    public function getPort(): int
    {
        return $this->_port;
    }


    public function getPath(): string
    {
        return $this->_path;
    }


    public function getQuery(): string
    {
        return $this->_query;
    }


    public function getAuthority(): string
    {
        $authority = $this->_host;
        if ($this->_port) {
            $authority .= ':' . $this->_port;
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
        return $this->_scheme . '://' . $this->getAuthority() . $this->_path . '?' . $this->_query . '#' . $this->_fragment;
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
        return $this->_fragment;
    }
}
