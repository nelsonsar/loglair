<?php

namespace Loglair;

use Respect\Validation\Validator as v;

class State
{
    private $cursorLocation = 0;
    private $fileSize = 0;
    private $filePath = '';

    public function __construct($cursorLocation, $fileSize, $filePath)
    {
        $this->validate($cursorLocation, $fileSize);
        $this->cursorLocation = $cursorLocation;
        $this->fileSize = $fileSize;
        $this->filePath = $filePath;
    }

    public function getCursorLocation()
    {
        return $this->cursorLocation;
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    private function validate($cursorLocation, $fileSize)
    {
        $this->validateCursorLocation($cursorLocation);
        $this->validateFileSize($fileSize);
    }

    private function validateCursorLocation($cursorLocation)
    {
        if (false === v::oneOf(v::equals(0, true), v::int()->positive())->validate($cursorLocation)) {
            $message = sprintf('Cursor location must be an integer equal or greater than 0', $cursorLocation);
            throw new \InvalidArgumentException($message);
        }
    }

    private function validateFileSize($fileSize)
    {
        if (false === v::oneOf(v::equals(0, true), v::int()->positive())->validate($fileSize)) {
            $message = sprintf('File size must be an integer equal or greater than 0', $fileSize); 
            throw new \InvalidArgumentException($message);
        }
    }
}
