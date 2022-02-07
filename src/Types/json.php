<?php

namespace Webshotapi\Client\Types;
use Webshotapi\Client\Interfaces\FileTypeInterface;

class Json implements FileTypeInterface {
    function getMime(): string {
        return 'application/json';
    }
}