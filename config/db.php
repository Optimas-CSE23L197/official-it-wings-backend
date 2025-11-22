<?php
require_once "env_loader.php";

function db() {
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $dbname = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $pass = $_ENV['DB_PASS'];
    $sslmode = $_ENV['DB_SSL'];

    $endpoint = explode('.', $host)[0];  

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=$sslmode;options=endpoint=$endpoint";

    try {
        return new PDO(
            $dsn,
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
}
