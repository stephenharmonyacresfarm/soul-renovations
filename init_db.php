<?php
// init_db.php - Run this file once to create the database tables
require_once 'db_config.php';

// Create photos table
$pdo->exec('
    CREATE TABLE IF NOT EXISTS photos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        alt_text VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
');

// Create reviews table
$pdo->exec('
    CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        location VARCHAR(100) NOT NULL,
        rating INT NOT NULL,
        review_text TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
');

// Create admin users table
$pdo->exec('
    CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )
');

// Create default admin user (username: admin, password: admin123)
// IMPORTANT: Change this password after first login!
$default_password = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT IGNORE INTO admin_users (username, password) VALUES (:username, :password)");
$stmt->execute([':username' => 'admin', ':password' => $default_password]);

echo "Database initialized successfully!<br>";
echo "Default admin credentials:<br>";
echo "Username: admin<br>";
echo "Password: admin123<br>";
echo "<strong>PLEASE CHANGE THE PASSWORD AFTER FIRST LOGIN!</strong><br>";
?>
