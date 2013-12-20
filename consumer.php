<?php

require_once __DIR__ . '/vendor/autoload.php';

$consumer = new Loglair\AngryCEO;

while (true) {
    $consumer->consume();
}
