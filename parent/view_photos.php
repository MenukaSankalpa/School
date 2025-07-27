<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "Application ID missing.";
    exit;
}

$app_id = intval($_GET['id']);

// Fetch the application data
$sql = "SELECT ebill_files, lbill_files FROM application_info WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $app_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No matching application found.";
    exit;
}

$row = $result->fetch_assoc();

$ebills = explode(", ", $row['ebill_files']);
$lbills = explode(", ", $row['lbill_files']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Uploaded Photos</title>
    <link rel="stylesheet" href="../css/va.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ccc;
        }

        img.thumb {
            width: 100px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .view-btn, .edit-btn {
            padding: 6px 10px;
            margin: 2px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            font-size: 13px;
        }

        .view-btn {
            background-color: #007bff;
        }

        .edit-btn {
            background-color: #ffc107;
            color: black;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Uploaded Photos</h2>

    <table>
        <thead>
        <tr>
            <th>Type</th>
            <th>File Name</th>
            <th>Preview</th>
            <th>View</th>
            <th>Edit</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ebills as $file): ?>
            <?php if (!empty($file)): ?>
                <tr>
                    <td>E-Bill</td>
                    <td><?= basename($file) ?></td>
                    <td><img src="../<?= htmlspecialchars($file) ?>" class="thumb" alt="Ebill Image"></td>
                    <td><a class="view-btn" href="../<?= htmlspecialchars($file) ?>" target="_blank">View</a></td>
                    <td><a class="edit-btn" href="edit_file.php?file=<?= urlencode($file) ?>&type=ebill">Edit</a></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php foreach ($lbills as $file): ?>
            <?php if (!empty($file)): ?>
                <tr>
                    <td>L-Bill</td>
                    <td><?= basename($file) ?></td>
                    <td><img src="../<?= htmlspecialchars($file) ?>" class="thumb" alt="Lbill Image"></td>
                    <td><a class="view-btn" href="../<?= htmlspecialchars($file) ?>" target="_blank">View</a></td>
                    <td><a class="edit-btn" href="edit_file.php?file=<?= urlencode($file) ?>&type=lbill">Edit</a></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="view_applications.php" style="color: blue;">← Back to Applications</a>
</div>

</body>
</html>
