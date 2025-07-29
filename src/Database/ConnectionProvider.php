<?php

namespace App\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use RuntimeException;

class ConnectionProvider
{
    public function getConnection(): Connection
    {
        try {
            $connection = DriverManager::getConnection([
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'host' => $_ENV['DB_HOST'],
                'port' => $_ENV['DB_PORT'],
                'driver' => 'pdo_mysql',
            ]);

            $connection->executeQuery('SELECT 1');

            return $connection;
        } catch (\Throwable $e) {
            throw new RuntimeException('Failed to connect to database: ' . $e->getMessage(), 0, $e);
        }
    }
}
