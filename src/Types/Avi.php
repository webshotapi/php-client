<?php

namespace Webshotapi\Client\Types;

use Webshotapi\Client\Interfaces\FileTypeInterface;

class Avi implements FileTypeInterface {
    function getMime(): string {
        return 'video/x-msvideo';
    }
}