<?php

namespace Webshotapi\Client\Types;

use Webshotapi\Client\Interfaces\FileTypeInterface;

class Webm implements FileTypeInterface {
    function getMime(): string {
        return 'video/webm';
    }
}