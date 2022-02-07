<?php

namespace Webshotapi\Client\Types;

use Webshotapi\Client\Interfaces\FileTypeInterface;

class Jpg implements FileTypeInterface {
    function getMime(): string {
        return 'image/jpeg';
    }
}