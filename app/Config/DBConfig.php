<?php

declare(strict_types = 1);

namespace App\Config;

/**
 * @property-read array $db
 */

class DBConfig
{
    private array $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            'db' => [
                'host' => $env['DB_HOST'],
                'user' => $env['DB_USER'],
                'passw' => $env['DB_PASS'],
                'db_name' => $env['DB_NAME'],
                'driver' => $env['driver'] ?? 'mysql'
            ]
        ];
    }
    
    public function __get($name): array|null
    {
        return $this->config[$name] ?? null;
    }
}