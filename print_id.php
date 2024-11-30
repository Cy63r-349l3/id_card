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

if (!$details || $details['status'] !== 'approved') {
    header("Location: id_card_status.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print ID Card</title>
    <style>
        .id-card {
            width: 4.375in;
            height: 4.125in;
            border: 3px solid #000;
            padding: 10px;
            text-align: center;
        }
        .id-card img {
            width: 70px;
            height: 70px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="id-card">
        <img src="assets/knp.jfif">
        <h5 class="text-success">Kano State Polytechnic</h5>
        <p>PMB 3401, Kano, Nigeria</p>
        <img src="<?= $details['passport_photo']; ?>" alt="Passport">
        <p><strong>Name:</strong> <?= $details['full_name']; ?></p>
        <p><strong>Department:</strong> <?= $details['department']; ?></p>
        <p><strong>Reg No:</strong> <?= $details['reg_no']; ?></p>
    </div>
</body>
</html>
