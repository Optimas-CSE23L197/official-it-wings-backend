<?php
require "../../config/db.php";
require "../../config/cors.php";

header("Content-Type: application/json");

// allow preflight
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// read json data
$data = json_decode(file_get_contents("php://input"), true);

// validate input
if (!isset($data['title']) || !isset($data['description'])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$title = $data['title'];
$description = $data['description'];

try {
    $db = db();

    $sql = "INSERT INTO jobs (title, description) VALUES (:title, :description)";
    $statement = $db->prepare($sql);
    $statement->execute([
        ':title' => $title,
        ':description' => $description
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Job added successfully"
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}