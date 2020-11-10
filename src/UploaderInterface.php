<?php

namespace Seven\File;

interface UploaderInterface
{
    public function upload(string $file): self;
}
