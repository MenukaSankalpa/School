<?php
session_start();
include '../db.php';

$adminId = $_SESSION['user_id'] ?? 0;
$appId = intval($_POST['app_id']);
$marks = intval($_POST['marks']);
$comments = trim($_POST['comments']);

// Only update if assigned
$sql = "UPDATE application_info SET marks=?, comments=? WHERE id=? AND assigned_admin_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isii", $marks, $comments, $appId, $adminId);
$stmt->execute();

header("Location: layout.php?page=view_applicant&id=$appId");
exit;
