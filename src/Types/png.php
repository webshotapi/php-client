<?php

namespace Webshotapi\Client\Types;
use Webshotapi\Client\Interfaces\FileTypeInterface;

class Png implements FileTypeInterface {

    function getMime(): string {
        return 'image/png';
    }

}