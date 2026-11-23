<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/javascript; charset=utf-8');

$body = @file_get_contents('http://internal/cdn/widget.js');
if ($body === false) {
    $body = @file_get_contents(__DIR__ . '/assets/js/widget.js');
}

echo $body;
