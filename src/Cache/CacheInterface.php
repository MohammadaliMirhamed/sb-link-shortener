<?php 

namespace App\Cache;


interface CacheInterface
{
    /**
     * @param string $key
     */
    public function get($key);

    /**
     * @param string $key
     * @param string $value
     * @param int $ttl
     */
    public function set($key, $value, $ttl = null);

    /**
     * @param string $key
     */
    public function delete($key);

    /**
     * @param string $key
     * @param int $ttl
     * @param callback $callback
     */
    public function remember($key, $callback, $ttl = null);
    
}
