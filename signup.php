<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration_number = $_POST['registration_number'] ?? '';
    $password = $_POST['password'] ?? '';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if registration_number already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE registration_number = ?");
        $stmt->execute([$registration_number]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "Error: Registration number already exists. Please choose a different one.";
        } else {
            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (registration_number, password, role) VALUES (?, ?, 'student')");
            $stmt->execute([$registration_number, $hashed_password]);
            echo "Signup successful! You can now <a href='login.php'>login</a>.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Signup</h2>
        <form method="post">
            <div class="mb-3">
                <label for="registration_number" class="form-label">Registration Number</label>
                <input type="text" class="form-control" id="registration_number" name="registration_number" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Signup</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
