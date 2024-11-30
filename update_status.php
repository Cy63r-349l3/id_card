<?php
require 'config.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE student_details SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $id])) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Failed to update status.";
    }
}
?>
