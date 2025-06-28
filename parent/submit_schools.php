<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_schools'])) {
    $selectedSchools = $_POST['selected_schools'];

    if (!isset($_SESSION['user_id'])) {
        echo "User not logged in.";
        exit();
    }

    if (count($selectedSchools) > 3) {
        echo "You can only select up to 3 schools.";
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Optional: save comma-separated list to `users` table
    $schoolList = implode(", ", $selectedSchools);
    $updateSql = "UPDATE users SET selected_schools=? WHERE id=?";
    $updateStmt = $conn->prepare($updateSql);
    if ($updateStmt) {
        $updateStmt->bind_param("si", $schoolList, $user_id);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        echo "User update error: " . $conn->error;
        exit();
    }

    // Delete old selections (if any)
    $deleteSql = "DELETE FROM applications WHERE user_id=?";
    $deleteStmt = $conn->prepare($deleteSql);
    if ($deleteStmt) {
        $deleteStmt->bind_param("i", $user_id);
        $deleteStmt->execute();
        $deleteStmt->close();
    }

    // Insert each selected school into applications table
    $insertStmt = $conn->prepare("INSERT INTO applications (user_id, school_name) VALUES (?, ?)");
    if (!$insertStmt) {
        echo "Insert error: " . $conn->error;
        exit();
    }

    foreach ($selectedSchools as $school) {
        $insertStmt->bind_param("is", $user_id, $school);
        $insertStmt->execute();
    }
    $insertStmt->close();

    // Save selected schools to session (if needed later)
    $_SESSION['selected_schools'] = $selectedSchools;

    // Redirect to next step
    header("Location: ../parent/information.php");
    exit();
} else {
    echo "No schools selected.";
}
?>
