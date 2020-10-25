<?php

namespace Seven\File;

use Seven\File\{UploaderTrait, UploaderInterface};

class Uploader implements UploaderInterface
{
    use UploaderTrait;

    protected $destination = __DIR__;

    protected $allowedTypes = [ 'jpg' => 'image/jpeg', 'png' => 'image/png' ];

    protected $sizeLimit =  5024768;
}
