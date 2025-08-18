<?php

namespace Webshotapi\Client\Types;

use Webshotapi\Client\Interfaces\FileTypeInterface;

class Gif implements FileTypeInterface {
    function getMime(): string {
        return 'image/gif';
    }
}