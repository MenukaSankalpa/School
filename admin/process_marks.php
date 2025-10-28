<?php
include '../db.php';

$appId = intval($_POST['app_id']);
$marks = intval($_POST['marks']);
$feedback = trim($_POST['feedback']);

if (!$appId) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    exit;
}

$stmt = $conn->prepare("UPDATE application_info SET marks=?, feedback=? WHERE id=?");
$stmt->bind_param("isi", $marks, $feedback, $appId);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true]);
?>
