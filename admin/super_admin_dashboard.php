<?php
include '../db.php';

// Count Admins
$admin_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM admins");
if ($result && $row = $result->fetch_assoc()) {
    $admin_count = $row['total'];
}

// Count Parents (role = 1 in users table)
$parent_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 1");
if ($result && $row = $result->fetch_assoc()) {
    $parent_count = $row['total'];
}

// Count Schools
$school_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM schools");
if ($result && $row = $result->fetch_assoc()) {
    $school_count = $row['total'];
}

// Count Complaints
$complaint_count = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM complaints");
if ($result && $row = $result->fetch_assoc()) {
    $complaint_count = $row['total'];
}
?>

<h1>Dashboard</h1>

<div class="dashboard-cards">
    <div class="card">
        <h2>Total Admins</h2>
        <p><?= $admin_count ?></p>
    </div>
    <div class="card">
        <h2>Parents Registered</h2>
        <p><?= $parent_count ?></p>
    </div>
    <div class="card">
        <h2>Number of Schools</h2>
        <p><?= $school_count ?></p>
    </div>
    <div class="card">
        <h2>Number of Complaints</h2>
        <p><?= $complaint_count ?></p>
    </div>
</div>

<style>
.dashboard-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}
.card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    flex: 1 1 200px;
    text-align: center;
}
.card h2 {
    margin: 0 0 10px;
    font-size: 20px;
    color: #333;
}
.card p {
    font-size: 30px;
    font-weight: bold;
    color: #007bff;
}
</style>
