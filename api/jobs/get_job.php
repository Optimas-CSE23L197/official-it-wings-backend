<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require "../../config/db.php";
require "../../config/cors.php";

header("Content-Type: application/json");

$db = db();

// Get value from query or body
$reference_number = $_GET['reference_number'] ?? null;

if (!$reference_number) {
    echo json_encode([
        "success" => false,
        "message" => "Missing reference_number"
    ]);
    exit;
}

// Fetch job with skillset
$sql = "
SELECT 
    j.job_id,
    j.job_title,
    j.short_description,
    j.full_description,
    j.location,
    j.openings,
    j.posted_date,
    j.expiry_date,
    j.experience_min,
    j.experience_max,
    j.reference_number,
    COALESCE(JSON_AGG(s.skill) FILTER (WHERE s.skill IS NOT NULL), '[]') AS skillset
FROM job_table j
LEFT JOIN job_skill_set s ON j.job_id = s.job_id
WHERE j.reference_number = :reference_number
GROUP BY j.job_id
";

try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":reference_number", $reference_number);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo json_encode([
            "success" => false,
            "message" => "Job not found"
        ]);
        exit;
    }

    if (is_string($data["skillset"])) {
        $data["skillset"] = json_decode($data["skillset"], true);
    }

    echo json_encode([
        "success" => true,
        "data" => $data
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
