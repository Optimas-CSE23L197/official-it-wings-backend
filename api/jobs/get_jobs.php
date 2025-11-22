<?php
require "./config/db.php";
require "./config/cors.php";

$db = db();
$statement = $db->query("
SELECT 
    j.*,
    COALESCE(
        JSON_AGG(s.skill) FILTER (WHERE s.skill IS NOT NULL),
        '[]'
    ) AS skill_set
FROM job_table j
LEFT JOIN job_skill_set s ON s.job_id = j.job_id
GROUP BY j.job_id
ORDER BY j.job_id DESC;
");

echo json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
