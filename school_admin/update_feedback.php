<?php
include '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $marks = intval($_POST['marks']);
    $feedback = trim($_POST['feedback']);

    $stmt = $conn->prepare("UPDATE application_info SET marks = ?, feedback = ? WHERE id = ?");
    $stmt->bind_param("isi", $marks, $feedback, $id);

    if ($stmt->execute()) {
        echo "✅ Feedback updated successfully!";
    } else {
        echo "❌ Failed to update feedback.";
    }
    $stmt->close();
    $conn->close();
}
?>
