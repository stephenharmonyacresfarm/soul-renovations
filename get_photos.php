<?php
// get_photos.php
header('Content-Type: application/json');

require_once 'db_config.php';

$result = $pdo->query('SELECT * FROM photos ORDER BY created_at DESC');
$rows = $result->fetchAll();

$photos = [];
foreach ($rows as $row) {
    $photos[] = [
        'id' => $row['id'],
        'url' => 'uploads/' . $row['filename'],
        'alt' => $row['alt_text']
    ];
}

echo json_encode($photos);
?>
