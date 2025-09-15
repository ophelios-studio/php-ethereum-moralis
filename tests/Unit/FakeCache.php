<?php

use Psr\SimpleCache\CacheInterface;

class FakeCache implements CacheInterface
{
    public array $store = [];
    public array $calls = [];

    public function get($key, $default = null): mixed
    {
        $this->calls[] = ['get', $key];
        return $this->store[$key] ?? $default;
    }

    public function set($key, $value, $ttl = null): bool
    {
        $this->calls[] = ['set', $key, $value, $ttl];
        $this->store[$key] = $value;
        return true;
    }

    public function delete($key): bool { unset($this->store[$key]); return true; }
    public function clear(): bool { $this->store = []; return true; }
    public function getMultiple($keys, $default = null): iterable { foreach ($keys as $k) { yield $this->get($k, $default); } }
    public function setMultiple($values, $ttl = null): bool { foreach ($values as $k => $v) { $this->set($k, $v, $ttl); } return true; }
    public function deleteMultiple($keys): bool { foreach ($keys as $k) { $this->delete($k); } return true; }
    public function has($key): bool { return array_key_exists($key, $this->store); }
}
