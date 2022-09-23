<?php

namespace Webshotapi\Client\Factories;

use Webshotapi\Client\Interfaces\FileTypeInterface;

class FileTypeFactory {
    public static function factory(string $name): FileTypeInterface{
        $cls = '\\Webshotapi\\Client\\Types\\' . ucfirst($name);

        return new $cls();
    }
}