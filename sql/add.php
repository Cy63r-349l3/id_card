<?php
require '../config.php';

// Insert a new admin account
$registration_number = 'admin';
$password = 'admin123'; // Admin's plain-text password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$role = 'admin';

try {
    $stmt = $pdo->prepare("INSERT INTO users (registration_number, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$registration_number, $hashedPassword, $role]);
    echo "Admin account created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
