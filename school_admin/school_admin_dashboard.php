<?php
session_start();
include '../db.php';

// Ensure logged-in user is a school admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '2') {
    header("Location: ../index.html"); // redirect to main login
    exit;
}

$admin_id = $_SESSION['user_id']; // session user ID

// Total assigned applicants
$applicant_count = 0;
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM application_info WHERE assigned_admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $applicant_count = $row['total'];
}

// Pending applicants
$pending_count = 0;
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM application_info WHERE status = 'pending' AND assigned_admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $pending_count = $row['total'];
}

// Approved/done applicants
$done_count = 0;
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM application_info WHERE status = 'approved' AND assigned_admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $done_count = $row['total'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>School Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            background-color: #f3f4f6;
        }

        /* Main content */
        .main-content {
            margin-left: 240px; /* width of sidebar */
            padding: 40px;
            flex: 1;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #1f2937;
        }

        .dashboard-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            flex: 1 1 250px;
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .card h2 {
            font-size: 20px;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .card p {
            font-size: 48px;
            font-weight: 600;
            color: #3b82f6;
            margin: 0;
        }

        .card i {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 30px;
            color: rgba(59,130,246,0.2);
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include 'layout.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Dashboard</h1>
        <div class="dashboard-cards">
            <div class="card">
                <i class="fas fa-users"></i>
                <h2>Total Assigned Applicants</h2>
                <p><?= $applicant_count ?></p>
            </div>
            <div class="card">
                <i class="fas fa-hourglass-half"></i>
                <h2>Pending Applicants</h2>
                <p><?= $pending_count ?></p>
            </div>
            <div class="card">
                <i class="fas fa-check-circle"></i>
                <h2>Approved / Done Applicants</h2>
                <p><?= $done_count ?></p>
            </div>
        </div>
    </div>

</body>
</html>
