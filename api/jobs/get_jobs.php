<?php
require "../../config/db.php";
require "../../config/cors.php";

header("Content-Type: application/json");

$db = db();

$query = "
SELECT 
    j.job_id,
    j.active,
    j.experience_max,
    j.experience_min,
    j.expiry_date,
    j.full_description,
    j.job_title,
    j.location,
    j.openings,
    j.posted_date,
    j.short_description,
    j.reference_number,
    COALESCE(JSON_AGG(s.skill) FILTER (WHERE s.skill IS NOT NULL), '[]') AS skillset
FROM job_table j
LEFT JOIN job_skill_set s ON j.job_id = s.job_id
GROUP BY j.job_id
ORDER BY j.job_id DESC
";

try {
    $stmt = $db->query($query);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert skillset from JSON string to array
    foreach ($jobs as &$job) {
        if (is_string($job["skillset"])) {
            $job["skillset"] = json_decode($job["skillset"], true);
        }
    }

    echo json_encode([
        "success" => true,
        "count"   => count($jobs),
        "data"    => $jobs
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error"   => $e->getMessage()
    ]);
}
