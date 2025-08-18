<?php

namespace Webshotapi\Client\Types;

use Webshotapi\Client\Interfaces\FileTypeInterface;

class Mov implements FileTypeInterface {
    function getMime(): string {
        return 'video/quicktime';
    }
}