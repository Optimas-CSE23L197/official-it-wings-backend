<?php
require_once "env_loader.php";

function db() {
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $dbname = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $pass = $_ENV['DB_PASS'];
    $sslmode = $_ENV['DB_SSL'];

    try {
        return new PDO(
            "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=$sslmode",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (Exception $e) {
        echo json_encode([
            "error" => $e->getMessage()
        ]);
        exit;
    }
}