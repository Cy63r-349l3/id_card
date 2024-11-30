<?php
require 'config.php';
session_start();

// Check if the user is logged in and their role is 'student'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Fetch the passport photo for the logged-in user based on user_id
$stmt = $pdo->prepare("SELECT passport_photo FROM student_details WHERE user_id = ?");
$stmt->execute([$user_id]);
$student_details = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if student details were found, otherwise set defaults
if (!$student_details) {
    $student_details = [
        'full_name' => 'N/A',
        'reg_no' => 'N/A',
        'department' => 'N/A',
        'passport_photo' => 'default.png', // Default image if no passport is uploaded
    ];
} else {
    // Fetch other details based on user_id
    $stmt = $pdo->prepare("SELECT full_name, reg_no, department FROM student_details WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $additional_details = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($additional_details) {
        $student_details = array_merge($student_details, $additional_details);
    }
}

// Verify if the passport photo exists; use the default if not
$passport_photo_path = '../uploads/' . $student_details['passport_photo'];
if (!file_exists($passport_photo_path) || empty($student_details['passport_photo'])) {
    $student_details['passport_photo'] = 'default.png';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Student Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="student_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="fill_form.php">Request ID Card</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="id_card_status.php">Check Status</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <h2>Welcome, <?= htmlspecialchars($student_details['full_name']) ?></h2>
        <p><strong>Registration Number:</strong> <?= htmlspecialchars($student_details['reg_no']) ?></p>
        <p><strong>Department:</strong> <?= htmlspecialchars($student_details['department']) ?></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
