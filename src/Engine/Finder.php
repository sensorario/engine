<?php

namespace Sensorario\Engine;

class Finder
{
    public function notExists(string $filename): bool
    {
        return !file_exists($filename);
    }

    public function getFileContent($filename): string
    {
        return file_get_contents($filename);
    }
}
