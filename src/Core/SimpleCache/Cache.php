<?php

namespace Core\SimpleCache;

class Cache implements CacheInterface
{
    private $cache = [];


    public function get($key, $default = null)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('Key must be a string');
        }

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        return $default;
    }


    public function set($key, $value, $ttl = null)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('Key must be a string');
        }

        $this->cache[$key] = $value;

        return true;
    }


    public function delete($key)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('Key must be a string');
        }

        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
            return true;
        }

        return false;
    }


    public function clear()
    {
        $this->cache = [];
        return true;
    }


    public function getMultiple($keys, $default = null)
    {
        if (!is_array($keys)) {
            throw new \InvalidArgumentException('Keys must be an array');
        }

        if (isset($this->cache[$keys])) {
            return $this->cache[$keys];
        }

        return $default;
    }


    public function setMultiple($values, $ttl = null)
    {
        if (!is_array($values)) {
            throw new \InvalidArgumentException('Values must be an array');
        }

        if (isset($this->cache[$values])) {
            $this->cache[$values] = $ttl;
            return true;
        }

        return false;
    }


    public function deleteMultiple($keys)
    {
        if (!is_array($keys)) {
            throw new \InvalidArgumentException('Keys must be an array');
        }

        if (isset($this->cache[$keys])) {
            unset($this->cache[$keys]);
            return true;
        }

        return false;
    }


    public function has($key)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('Key must be a string');
        }

        return isset($this->cache[$key]);
    }
}
