<?php
// get_reviews.php
header('Content-Type: application/json');

require_once 'db_config.php';

$result = $pdo->query('SELECT * FROM reviews ORDER BY created_at DESC');
$rows = $result->fetchAll();

$reviews = [];
foreach ($rows as $row) {
    $reviews[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'location' => $row['location'],
        'rating' => $row['rating'],
        'text' => $row['review_text']
    ];
}

echo json_encode($reviews);
?>
