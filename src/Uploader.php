<?php

namespace Seven\File;

use Seven\File\{UploaderInterface, UploaderTrait};

class Uploader implements UploaderInterface
{

      /**
    * upload destination directory
    *
    * @property string destination
    * @example __DIR__.'/cdn/'
    */
    protected $destination;

    /**
    * Allowed Mime and file types
    *
    * @property array allowed types
    * @example = [ 'jpg' => 'image/jpeg', 'png' => 'image/png' ]
    *
    */
    protected $allowedTypes  = [ 'jpg' => 'image/jpeg', 'png' => 'image/png' ];

    /**
    * upload size limit
    *
    * @property int sizeLimit
    * @example 5024768;  approximately 5mb
    */
    protected $sizeLimit = 5024768;

    /**
    * upload file; already defined and declared in the Uploader trait
    *
    * @property string file: the key in the $_FILE globals passed
    *
    */

    use UploaderTrait;

    public function __construct(
        string $destination,
        array $allowedTypes,
        int $sizeLimit
    ) {
        $this->destination = $destination;
        $this->allowedTypes = $allowedTypes;
        $this->sizeLimit = $sizeLimit;
    }
}
