<?php
// Database configuration
define('DB_HOST', 'localhost');      // Hostname (e.g., localhost for local server)
define('DB_NAME', 'id_card_system');  // Name of your database
define('DB_USER', 'root');           // Database username (default is 'root' for XAMPP)
define('DB_PASSWORD', '');           // Database password (leave blank for XAMPP)

// Create a connection to the database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
