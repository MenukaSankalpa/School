<?php
include '../db.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$appId = intval($data['app_id'] ?? 0);

if (!$appId) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Application ID']);
    exit;
}

// Get applicant info
$app = $conn->query("SELECT user_id, child_full_name FROM application_info WHERE id=$appId")->fetch_assoc();
if (!$app) {
    echo json_encode(['status' => 'error', 'message' => 'Applicant not found']);
    exit;
}

$msg = "Your child's application (" . $app['child_full_name'] . ") has been reviewed. Please check your dashboard for updates.";

$stmt = $conn->prepare("INSERT INTO messages (user_id, message, created_at) VALUES (?, ?, NOW())");
$stmt->bind_param("is", $app['user_id'], $msg);
$stmt->execute();
$stmt->close();

echo json_encode(['status' => 'success']);
