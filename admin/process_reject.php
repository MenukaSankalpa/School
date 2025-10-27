<?php
include '../db.php';
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $app_id = intval($_POST['app_id']);
    if($app_id){
        $stmt = $conn->prepare("UPDATE application_info SET status='rejected' WHERE id=?");
        $stmt->bind_param("i",$app_id);
        if($stmt->execute()){
            $_SESSION['flash'] = "Application rejected successfully!";
        } else {
            $_SESSION['flash'] = "Error: ".$conn->error;
        }
    } else {
        $_SESSION['flash'] = "Invalid application ID!";
    }
}
header("Location: applicant_list.php");
exit;
