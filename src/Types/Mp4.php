<?php

namespace Webshotapi\Client\Types;

use Webshotapi\Client\Interfaces\FileTypeInterface;

class Mp4 implements FileTypeInterface {
    function getMime(): string {
        return 'video/mp4';
    }
}