<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appId = intval($_POST['app_id']);
    $adminId = intval($_POST['admin_id']);

    if (!$appId || !$adminId) {
        die("Invalid input.");
    }

    // Update application with assigned admin + mark as approved
    $sql = "UPDATE application_info 
            SET assigned_admin_id = ?, status = 'approved' 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $adminId, $appId);

    if ($stmt->execute()) {
        header("Location: applicant_list.php?msg=Admin assigned successfully");
        exit();
    } else {
        die("Error: " . $conn->error);
    }
} else {
    die("Invalid request method.");
}
