<?php

require_once __DIR__ . '/vendor/autoload.php';

use Loglair\FileWatcher;
use Loglair\Observer\ApacheLogParser;

$fileWatcher = new FileWatcher('/var/log/apache2/access.log');
$fileWatcher->attach(new ApacheLogParser);
$fileWatcher->startWatch();

while (true) {
    if ($fileWatcher->fileHasChanged()) {
        $fileWatcher->notify();
    }
}
