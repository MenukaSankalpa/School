<?php
include '../db.php';
session_start();

$data = $_POST;
$appId = intval($data['app_id'] ?? 0);
$feedback = trim($data['feedback'] ?? '');

if (!$appId) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid applicant']);
    exit;
}

$stmt = $conn->prepare("UPDATE application_info SET feedback=? WHERE id=?");
$stmt->bind_param("si", $feedback, $appId);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update']);
}
$stmt->close();
