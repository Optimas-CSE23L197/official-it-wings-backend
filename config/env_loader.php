<?php
use Dotenv\Dotenv;

$envFile = __DIR__ . '/../.env';

if (file_exists($envFile)) {
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../");
    $dotenv->load();
}
