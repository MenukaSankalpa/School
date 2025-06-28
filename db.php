<?php
$conn = new mysqli("localhost", "root", "", "school_admission_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
