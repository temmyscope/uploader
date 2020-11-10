<?php

namespace Seven\File;

use Seven\Vars\Strings;
use Seven\File\UploaderInterface;

/**
* Should be used in a model class that defines all of the initialised variables
*
* @package ModelTrait
* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
*
*/

trait UploaderTrait
{
    protected $status = false;

    protected $statusMessage = "";

    protected $name = null;

    protected $type = null;

    protected $fullName = null;

    protected $size;

    public function status(): bool
    {
        return $this->status;
    }

    public function statusMessage(): string
    {
        return $this->statusMessage;
    }

    public function name()
    {
        return $this->name;
    }

    public function fullName()
    {
        return $this->fullName;
    }

    /**
    * @param array | string $file
    *
    * @return UploaderInterface
    */
    public function upload(string $file): UploaderInterface
    {
        $file = $_FILES[$file];
        if (is_null($file['name'])) {
            $this->status = false;
            $this->statusMessage = "No file was sent.";
            return $this;
        }
        if ($file['size'] > $this->sizeLimit) {
            $this->status = false;
            $this->statusMessage = "The file limit of " . ($this->sizeLimit / 1048576) . " mb has been exceeded";
            return $this;
        }

        [$target, $type] = $this->getNewName($file['name']);

        if (empty($file["tmp_name"])) {
            $this->status = false;
            $this->statusMessage = "This file is damaged, use another file.";
            return $this;
        }

        if ($this->move($file["tmp_name"], $target)) {
            if ($this->allowed($type, $this->getMime($target))) {
                $this->status = true;
                $this->fullName = $target;
                $this->type = $type;
                $this->size = $file['size'];
                return $this;
            } else {
                rmdir($target);
                $this->status = false;
                $this->statusMessage = "This file type or mime is not allowed";
                return $this;
            }
        }
        $this->status = false;
        $this->statusMessage = "An unknown error occurred.";
        return $this;
    }

    public function imageResize($newWidth, $newHeight)
    {
        $ext = $this->type;
        $file = $this->name;
        if (!$this->status) {
            $this->statusMessage = "File was not uploaded.";
            return $this;
        }
        list($width, $height) = getimagesize($file);
        if ($ext === "jpg" || $ext === "jpeg") {
            $src = imagecreatefromjpeg($file);
            $dst = imagecreatetruecolor($newHeight, $newHeight);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagejpeg($dst, $file);
        } elseif ($ext === "png") {
            $src = imagecreatefrompng($file);
            $dst = imagecreatetruecolor($newHeight, $newHeight);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newHeight, $newHeight, $width, $height);
            imagepng($dst, $file);
        } elseif ($ext === "gif") {
            $src = imagecreatefromgif($file);
            $dst = imagecreatetruecolor($newHeight, $newHeight);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newHeight, $newHeight, $width, $height);
            imagegif($dst, $file);
        }
        return $this;
    }

    private function move($source, $destination): bool
    {
        return move_uploaded_file($source, $destination);
    }

    private function getMime($filename): string
    {
        return mime_content_type($filename);
    }

    /**
    * @param
    *
    * @return bool
    */
    private function allowed($type, $mime): bool
    {
        return ( array_key_exists($type, $this->allowedTypes) && $this->allowedTypes[$type] === $mime );
    }

    /**
    *
    * @param string $name
    *
    * @return array containing target name of file and file type
    */
    private function getNewName(string $name): array
    {
        $name = basename($name);
        $type = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $name = Strings::uniqueId($name);
        $this->name = $this->createFileName('', $name, $type);

        return [
            $this->createFileName($this->destination, $name, $type), $type
        ];
    }

    protected function createFileName(string $directory, string $name, string $extension): string
    {
        $trueName = rtrim($directory, '/') . $name;
        return implode('.', [ $trueName, $extension ]);
    }
}
