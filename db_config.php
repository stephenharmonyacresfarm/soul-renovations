<?php
// db_config.php - Database configuration
// Update these values with your InfinityFree MySQL credentials

$db_host = 'localhost';          // InfinityFree will provide this (e.g., sql123.infinityfree.com)
$db_name = 'your_database_name'; // Your database name from InfinityFree
$db_user = 'your_username';      // Your database username from InfinityFree
$db_pass = 'your_password';      // Your database password from InfinityFree

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
