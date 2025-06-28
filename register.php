<?php

$conn = new mysqli("localhost", "root", "", "school_admission_system");

if($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}


$username = $_POST['username'];
$email = $_POST['email'];
$child_name = $_POST['child_name'];
$role = $_POST['role'];
$password = md5($_POST['password']);

// check anyone trying to register as super admin 

if ($role == '3') {
    echo "<script>alert('Super Admin already exists. You can't register as Super Admin.'); window.location.href='index.html';</script>";
    exit();
}

// child's name only if role is parent registration
$child_name = ($role == '1') ? $_POST['child_name'] : 'N/A';

// insert new user details

$sql = "INSERT INTO users (username, email, child_name, role, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssis", $username, $email, $child_name, $role, $password );

if ($stmt->execute()) {
    echo "<script>alert('You have Registered successfully!');window.location.href='index.html'</script>";
} else {
    echo "<script>alert('Registration failed or Email already used.'); window.location.href='index.html'</script>";
}

$conn->close();

?>