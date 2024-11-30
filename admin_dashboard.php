<?php
require 'config.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch Pending Requests
$pendingStmt = $pdo->prepare("SELECT * FROM student_details WHERE status = 'pending'");
$pendingStmt->execute();
$pendingRequests = $pendingStmt->fetchAll();

// Fetch Approved Requests
$approvedStmt = $pdo->prepare("SELECT * FROM student_details WHERE status = 'approved'");
$approvedStmt->execute();
$approvedRequests = $approvedStmt->fetchAll();

// Fetch Rejected Requests
$rejectedStmt = $pdo->prepare("SELECT * FROM student_details WHERE status = 'rejected'");
$rejectedStmt->execute();
$rejectedRequests = $rejectedStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    
                </ul>
            </div>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Pending Requests -->
        <section id="pending">
            <h3>Pending Requests</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Reg No</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingRequests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['full_name']); ?></td>
                            <td><?= htmlspecialchars($request['department']); ?></td>
                            <td><?= htmlspecialchars($request['reg_no']); ?></td>
                            <td>
                                <form method="POST" action="update_status.php" style="display: inline-block;">
                                    <input type="hidden" name="id" value="<?= $request['id']; ?>">
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                                <form method="POST" action="update_status.php" style="display: inline-block;">
                                    <input type="hidden" name="id" value="<?= $request['id']; ?>">
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Approved IDs -->
        <section id="approved" class="mt-5">
            <h3>Approved IDs</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Reg No</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($approvedRequests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['full_name']); ?></td>
                            <td><?= htmlspecialchars($request['department']); ?></td>
                            <td><?= htmlspecialchars($request['reg_no']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Rejected IDs -->
        <section id="rejected" class="mt-5">
            <h3>Rejected IDs</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Reg No</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rejectedRequests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['full_name']); ?></td>
                            <td><?= htmlspecialchars($request['department']); ?></td>
                            <td><?= htmlspecialchars($request['reg_no']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>