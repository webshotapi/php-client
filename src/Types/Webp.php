<?php

namespace Webshotapi\Client\Types;
use Webshotapi\Client\Interfaces\FileTypeInterface;

class Webp implements FileTypeInterface {

    function getMime(): string {
        return 'image/webp';
    }

}