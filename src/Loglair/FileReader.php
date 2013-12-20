<?php

namespace Loglair;

use Respect\Validation\Validator as v;

class FileReader
{
    private $filePath = '';

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function readExcerpt($from, $to)
    {
        $this->validateCoordinates($from, $to);

        $excerpt = '';
        $fileHandler = fopen($this->filePath, 'r');

        fseek($fileHandler, $from);

        $bytesToRead = $this->calculateBytesToRead($from, $to);

        while ($this->calculateBytesAlreadyRead($fileHandler) < $to) {
            $excerpt .= stream_get_contents($fileHandler, $bytesToRead);
        }

        fclose($fileHandler);

        return $excerpt;
    }

    private function validateCoordinates($from, $to)
    {
        if (false === v::oneOf(v::equals(0, true), v::int()->positive())->validate($from)) {
            throw new \InvalidArgumentException('Start position for read must be a positive integer');
        }

        if (false === v::oneOf(v::equals(0, true), v::int()->positive())->validate($to)) {
            throw new \InvalidArgumentException('Final position for read must be a positive integer');
        }
    }

    private function calculateBytesToRead($from, $to)
    {
        return ($to - $from);
    }

    private function calculateBytesAlreadyRead($fileHandler)
    {
        return ftell($fileHandler);
    }
}
