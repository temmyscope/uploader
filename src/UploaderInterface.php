<?php

namespace Seven\File\Uploader;

interface UploaderInterface
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
    protected $allowedTypes;

    /**
    * upload size limit
    *
    * @property int sizeLimit
    * @example 5024768;  approximately 5mb
    */
    protected $sizeLimit;

    /**
    * upload file; already defined and declared in the Uploader trait
    *
    * @property string file: the key in the $_FILE globals passed
    *
    */

    public function upload(string $file): UploaderInterface;
}
