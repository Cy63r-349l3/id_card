<?php
require 'config.php';
session_start();

if ($_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM student_details WHERE user_id = ?");
$stmt->execute([$user_id]);
$details = $stmt->fetch();

if (!$details) {
    $message = "You haven't submitted your details yet.";
} elseif ($details['status'] === 'pending') {
    $message = "Your ID card is still under review by the admin.";
} elseif ($details['status'] === 'rejected') {
    $message = "Your ID card request was rejected. Please resubmit your details.";
} elseif ($details['status'] === 'approved') {
    $approved = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ID Card Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>ID Card Status</h2>
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?= $message; ?></div>
        <?php endif; ?>
        <?php if (isset($approved)): ?>
            <div class="card" style="width: 3.375in; height: 4.125in; border: 3px solid #000; padding: 10px;">
            <img src="assets/knp.jfif" class="id">    
            <h5 class="text-center text-success">Kano State Polytechnic</h5>
                <p class="text-center">PMB 3401, Kano, Nigeria</p>
                <div class="text-center">
                    <img src="<?= $details['passport_photo']; ?>" alt="Passport" style="width: 90px; height: 90px;  border-radius: 50px;">
                </div>
                <p><strong>Name:</strong> <?= $details['full_name']; ?></p>
                <p><strong>Department:</strong> <?= $details['department']; ?></p>
                <p><strong>Reg No:</strong> <?= $details['reg_no']; ?></p>
            </div>
            <a href="print_id.php" class="btn btn-success mt-3">Print ID Card</a>
        <?php endif; ?>
    </div>
</body>
</html>
