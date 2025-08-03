<?php
session_start();
include '../db.php';

$page = $_GET['page'] ?? 'super_admin_dashboard'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>

    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f8;
            display: flex;
            color: #2c3e50;
        }

        .sidebar {
            width: 240px;
            background-color: #1e293b;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 30px 20px;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 40px;
            text-align: center;
            font-weight: 600;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #334155;
        }

        .material-icons {
            font-size: 20px;
        }

        .content {
            margin-left: 240px;
            padding: 30px;
            width: calc(100% - 240px);
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="layout.php?page=super_admin_dashboard"><span class="material-icons">dashboard</span>Dashboard</a>
    <a href="layout.php?page=admin_list"><span class="material-icons">people</span>Admins</a>
    <a href="layout.php?page=applicant_list"><span class="material-icons">person</span>Applicants</a>
    <a href="layout.php?page=view_schools"><span class="material-icons">school</span>School Details</a>
    <a href="layout.php?page=parent_complaints"><span class="material-icons">report</span>Parent Complaints</a>
    <a href="../login.php"><span class="material-icons">logout</span>Logout</a>
</div>

<!-- Dynamic Content -->
<div class="content">
    <?php
    $file = $page . '.php';
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        include $path;
    } else {
        echo "<h2>Page not found.</h2>";
    }
    ?>
</div>


</body>
</html>
