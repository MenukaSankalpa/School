<?php
include '../db.php';

$id = $_POST['id'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];


if (password_get_info($password)['algo'] === 0) {
    $password = password_hash($password, PASSWORD_DEFAULT);
}

$sql = "UPDATE admins SET username = ?, email = ?, password = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $username, $email, $password, $id);
$stmt->execute();

header("Location: admin_list.php");
