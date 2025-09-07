<?php
include '../db.php';

// Count Pending Applications
$pending_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM application_info WHERE status = 'pending'");
if ($result && $row = $result->fetch_assoc()) {
    $pending_count = $row['total'];
}

// Count Approved/Done Applications
$done_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM application_info WHERE status = 'approved'");
if ($result && $row = $result->fetch_assoc()) {
    $done_count = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: #f3f4f6;
            display: flex;
        }
        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #1e40af;
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #2563eb;
        }
        /* Main content */
        .main-content {
            flex: 1;
            padding: 30px;
        }
        h1 {
            margin-bottom: 30px;
        }
        .dashboard-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            flex: 1 1 200px;
            text-align: center;
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .card h2 {
            margin: 0 0 15px;
            font-size: 20px;
            color: #111827;
        }
        .card p {
            font-size: 36px;
            font-weight: bold;
            color: #2563eb;
        }
        .pending { color: #f59e0b; }
        .done { color: #10b981; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="applicant_list.php">Applicants</a>
        <a href="assign_admin.php">Assign Admin</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h1>Applicant Dashboard</h1>
        <div class="dashboard-cards">
            <div class="card">
                <h2>Pending Applications</h2>
                <p class="pending"><?= $pending_count ?></p>
            </div>
            <div class="card">
                <h2>Approved / Done Applications</h2>
                <p class="done"><?= $done_count ?></p>
            </div>
        </div>
    </div>

</body>
</html>
