<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Hash password
    if (password_get_info($password)['algo'] === 0) {
        $password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Use correct table name (users)
    $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error preparing query: " . $conn->error;
        exit;
    }

    $stmt->bind_param("sssi", $username, $email, $password, $id);

    if ($stmt->execute()) {
        echo "success"; // response for AJAX
    } else {
        echo "Error updating admin: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
