<?php

namespace Webshotapi\Client\Types;

use Webshotapi\Client\Interfaces\FileTypeInterface;

class Pdf implements FileTypeInterface {
    function getMime(): string {
        return 'application/pdf';
    }
}