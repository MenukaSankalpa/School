<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['file']) || !isset($_GET['type'])) {
    echo "Missing file or type.";
    exit;
}

$oldFile = $_GET['file'];
$type = $_GET['type'];


$filename = basename($oldFile);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Replace File</title>
    <link rel="stylesheet" href="../css/va.css">
    <style>
        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 12px;
        }

        img {
            width: 150px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        input[type="file"], button {
            padding: 10px;
            margin: 10px 0;
        }

        button {
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: blue;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Replace <?= strtoupper($type) ?> File</h2>
    <p><strong>Old File:</strong> <?= htmlspecialchars($filename) ?></p>
    <img src="../<?= htmlspecialchars($oldFile) ?>" alt="Old file">

    <form action="replace_file_action.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="old_file" value="<?= htmlspecialchars($oldFile) ?>">
        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

        <label for="new_file">Upload New File:</label><br>
        <input type="file" name="new_file" required><br>
        <button type="submit">Replace</button>
    </form>

    <a href="javascript:history.back()">← Back</a>
</div>
</body>
</html>
