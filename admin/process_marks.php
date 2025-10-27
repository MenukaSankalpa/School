<?php
include '../db.php';
session_start();

if($_SERVER['REQUEST_METHOD']==='POST'){
    $appId = intval($_POST['app_id']);
    $marks = intval($_POST['marks']);
    $comments = $_POST['comments'];

    $sql = "UPDATE application_info SET marks=?, comments=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $marks, $comments, $appId);
    $stmt->execute();
    echo "success";
}
?>
