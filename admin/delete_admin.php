<?php
include '../db.php';

$id = $_GET['id'];
$conn->query("DELETE FROM admins WHERE id = $id");

header("Location: admin_list.php");
