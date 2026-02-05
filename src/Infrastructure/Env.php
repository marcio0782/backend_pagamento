<?php

declare(strict_types=1);

namespace App\Infrastructure;

final class Env
{
    private static bool $loaded = false;

    public static function load(string $path): void
    {
        if (self::$loaded) {
            return;
        }

        if (!is_file($path)) {
            self::$loaded = true;
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            self::$loaded = true;
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = self::stripQuotes(trim($value));

            if ($key === '') {
                continue;
            }

            if (getenv($key) !== false || array_key_exists($key, $_ENV)) {
                continue;
            }

            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }

        self::$loaded = true;
    }

    public static function getString(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);
        if ($value === false) {
            $value = $_ENV[$key] ?? null;
        }

        if ($value === null || $value === '') {
            return $default;
        }

        return (string) $value;
    }

    public static function getInt(string $key, int $default): int
    {
        $value = self::getString($key);
        if ($value === null) {
            return $default;
        }

        $parsed = filter_var($value, FILTER_VALIDATE_INT);
        return $parsed !== false ? (int) $parsed : $default;
    }

    private static function stripQuotes(string $value): string
    {
        if (strlen($value) < 2) {
            return $value;
        }

        $first = $value[0];
        $last = $value[strlen($value) - 1];
        if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

