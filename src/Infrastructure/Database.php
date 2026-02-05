<?php

declare(strict_types=1);

namespace App\Infrastructure;

use PDO;

final class Database
{
    public static function connect(): PDO
    {
        Env::load(__DIR__ . '/../../.env');

        $driver = Env::getString('DB_CONNECTION', 'pgsql');
        if ($driver !== 'pgsql') {
            throw new \RuntimeException('Unsupported DB_CONNECTION: ' . (string) $driver);
        }

        $host = Env::getString('DB_HOST', 'localhost');
        $port = Env::getInt('DB_PORT', 5432);
        $database = Env::getString('DB_DATABASE', 'banco_pagamento');
        $username = Env::getString('DB_USERNAME', 'postgres');
        $password = Env::getString('DB_PASSWORD', '');

        if ($host === null || $database === null || $username === null || $password === null) {
            throw new \RuntimeException('Database environment variables are not properly configured');
        }

        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $host,
            $port,
            $database
        );

        return new PDO(
            $dsn,
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
}

