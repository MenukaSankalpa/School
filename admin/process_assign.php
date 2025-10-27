<?php
include '../db.php';
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $app_id = intval($_POST['app_id']);
    $admin_id = intval($_POST['admin_id']);

    if($app_id && $admin_id){
        $stmt = $conn->prepare("UPDATE application_info SET assigned_admin_id=?, status='approved' WHERE id=?");
        $stmt->bind_param("ii", $admin_id, $app_id);
        if($stmt->execute()){
            $_SESSION['flash'] = "Admin assigned successfully!";
        } else {
            $_SESSION['flash'] = "Error: " . $conn->error;
        }
    } else {
        $_SESSION['flash'] = "Invalid input!";
    }
}
header("Location: applicant_list.php");
exit;
