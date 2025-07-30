<?php
session_start();
include '../db.php';

$sql = "SELECT id, child_full_name, applicant_full_name, dob, resident_district, created_at, status 
        FROM application_info ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        /* Layout */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }

        .sidebar {
            width: 220px;
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f9f9f9;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #ecf0f1;
        }

        .actions a {
            margin-right: 8px;
            color: #2980b9;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .status {
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-size: 13px;
        }

        .status.pending {
            background-color: #f39c12;
        }

        .status.approved {
            background-color: #27ae60;
        }

        .status.rejected {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">📋 Dashboard</a>
    <a href="admin_list.php">👤 Admins</a>
    <a href="applicants_list.php">🧑‍💼 Applicants</a>
    <a href="school_details.php">🏫 School Details</a>
    <a href="../auth/logout.php">🚪 Logout</a>
</div>

<div class="content">
    <h1>Application Submissions</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Child Name</th>
                    <th>Applicant</th>
                    <th>Date of Birth</th>
                    <th>District</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['child_full_name']) ?></td>
                    <td><?= htmlspecialchars($row['applicant_full_name']) ?></td>
                    <td><?= $row['dob'] ?></td>
                    <td><?= $row['resident_district'] ?></td>
                    <td><?= $row['created_at'] ?? '-' ?></td>
                    <td>
                        <span class="status <?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="view_application.php?id=<?= $row['id'] ?>">View</a>
                        <a href="approve_application.php?id=<?= $row['id'] ?>">Approve</a>
                        <a href="reject_application.php?id=<?= $row['id'] ?>">Reject</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No applications found.</p>
    <?php endif; ?>
</div>

</body>
</html>
