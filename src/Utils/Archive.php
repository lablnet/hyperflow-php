<?php

namespace HyperFlow\Utils;

class Archive
{
    public static function append(string $filePath, array $data): void
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $json = json_encode($data);
        file_put_contents($filePath, $json . PHP_EOL, FILE_APPEND);
    }

    public static function readAll(string $filePath): array
    {
        if (!file_exists($filePath)) return [];
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return array_map(fn($line) => json_decode($line, true), $lines);
    }
}
