<?php

namespace Loglair\Observer;

use Grok;
use Loglair\FileReader;
use Loglair\OutputHandler\AMQP;

class ApacheLogParser implements \SplObserver
{
    private $outputHandler = null;

    public function __construct()
    {
        $this->outputHandler = new AMQP;
    }

    public function update(\SplSubject $subject)
    {
        $state = $subject->getState();

        $fileReader = new FileReader($state->getFilePath());

        $content = $fileReader->readExcerpt($state->getCursorLocation(), $state->getFileSize());

        $lines = explode("\n", trim($content));

        foreach ($lines as $line) {
            $this->outputHandler->send(json_encode($this->parse($content)));
        }
    }

    public function parse($content)
    {
        $grok = new Grok;

        return $grok->parse('%{COMBINEDAPACHELOG}', $content, 'm');
    }
}
