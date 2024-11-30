<?php
require 'config.php';
session_start();

if ($_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $department = $_POST['department'];
    $school = $_POST['school'];
    $reg_no = $_POST['reg_no'];
    $passport_photo = '';

    // Handle File Upload
    if ($_FILES['passport_photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $passport_photo = $upload_dir . basename($_FILES['passport_photo']['name']);
        move_uploaded_file($_FILES['passport_photo']['tmp_name'], $passport_photo);
    }

    // Check if the record already exists
    $stmt = $pdo->prepare("SELECT * FROM student_details WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $stmt = $pdo->prepare("UPDATE student_details SET full_name = ?, department = ?, school = ?, reg_no = ?, passport_photo = ?, status = 'pending' WHERE user_id = ?");
        $stmt->execute([$full_name, $department, $school, $reg_no, $passport_photo, $user_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO student_details (user_id, full_name, department, school, reg_no, passport_photo, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$user_id, $full_name, $department, $school, $reg_no, $passport_photo]);
    }
    $success = "Details submitted successfully! Waiting for admin approval.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fill ID Card Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Fill Your ID Card Details</h2>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" name="department" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="school" class="form-label">School</label>
                <input type="text" name="school" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="reg_no" class="form-label">Registration Number</label>
                <input type="text" name="reg_no" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="passport_photo" class="form-label">Upload Passport Photo</label>
                <input type="file" name="passport_photo" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
