<?php

namespace App\Domain;

use App\Cache\CacheAdapter;
use App\Database\DbConnection;

abstract class DomainAbstraction
{
    /**
     * @var DbConnection
     */
    protected $db;

    /**
     * @var CacheAdapter
     */
    protected $cache;

    /**
     * DomainAbstraction constructor.
     * @param DbConnection $db
     */
    public function __construct()
    {
        $this->db = DbConnection::getInstance();
        $this->cache = new CacheAdapter();
    }
}
