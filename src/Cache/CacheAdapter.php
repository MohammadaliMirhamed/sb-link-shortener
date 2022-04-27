<?php 

namespace App\Cache;

use Predis\Client;

class CacheAdapter implements CacheInterface
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'scheme' => $_ENV['REDIS_SCHEME'],
            'host'   => $_ENV['REDIS_HOST'],
            'port'   => $_ENV['REDIS_PORT'],
        ]);
    }

    /**
     * @param string $key
     */
    public function get($key)
    {
        return json_decode($this->client->get($key), true);
    }
    
    /**
     * @param string $key
     * @param string $value
     * @param int $ttl
     */
    public function set($key, $value, $ttl = null)
    {
        $this->client->set($key, json_encode($value));
        if ($ttl) {
            $this->client->expire($key, $ttl);
        }
    }

    /**
     * @param string $key
     */
    public function delete($key)
    {
        $this->client->del($key);
    }

    /**
     * @param string $key
     * @param int $ttl
     * @param callback $callback
     */
    public function remember($key, $callback, $ttl = null)
    {
        $value = json_decode($this->client->get($key), true);

        if ($value) {
            return $value;
        }

        $value = $callback();
        $this->client->set($key, json_encode($value));

        if ($ttl) {
            $this->client->expire($key, $ttl);
        }

        return $value;
    }
}
