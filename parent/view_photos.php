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

// Fetch file paths from database
$sql = "SELECT ebill_files, lbill_files FROM application_info WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $app_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No matching application found.";
    exit;
}

$row = $result->fetch_assoc();

// --- DEBUG: Uncomment to check DB content ---
// echo "<pre>";
// echo "EBILL FILES:\n";
// var_dump($row['ebill_files']);
// echo "\nLBILL FILES:\n";
// var_dump($row['lbill_files']);
// exit;

function parseFileList($fileList) {
    // Handle null or empty strings gracefully
    if (!$fileList || trim($fileList) === '') {
        return [];
    }
    // Explode by comma, trim spaces, filter out empties
    return array_filter(array_map('trim', explode(',', $fileList)));
}

$ebills = parseFileList($row['ebill_files']);
$lbills = parseFileList($row['lbill_files']);
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

        .lbill-gallery {
            margin-top: 40px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .lbill-gallery img {
            width: 90px;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .lbill-gallery img:hover {
            transform: scale(1.1);
        }

        .section-title {
            margin-top: 40px;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Uploaded Photos</h2>

    <?php if (!empty($ebills)): ?>
        <h3>E-Bill Images</h3>
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
                <?php
                // Sanitize path, ensure it starts with "uploads/ebill/" to avoid broken paths
                $file = trim($file);
                if (!$file || !str_starts_with($file, 'uploads/ebill/')) {
                    continue;
                }
                ?>
                <tr>
                    <td>E-Bill</td>
                    <td><?= htmlspecialchars(basename($file)) ?></td>
                    <td><img src="../<?= htmlspecialchars($file) ?>" class="thumb" alt="Ebill"></td>
                    <td><a class="view-btn" href="../<?= htmlspecialchars($file) ?>" target="_blank" rel="noopener noreferrer">View</a></td>
                    <td><a class="edit-btn" href="edit_file.php?file=<?= urlencode($file) ?>&type=ebill">Edit</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No E-Bill images uploaded.</p>
    <?php endif; ?>

    <?php if (!empty($lbills)): ?>
        <div class="section-title">L-Bill Images</div>
        <div class="lbill-gallery">
            <?php 
            $count = 0;
            foreach ($lbills as $file): 
                $file = trim($file);
                if (!$file || !str_starts_with($file, 'uploads/lbill/')) {
                    continue;
                }
                if ($count++ >= 5) break; // show max 5 images
            ?>
                <a href="../<?= htmlspecialchars($file) ?>" target="_blank" rel="noopener noreferrer">
                    <img src="../<?= htmlspecialchars($file) ?>" alt="Lbill Image">
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No L-Bill images uploaded.</p>
    <?php endif; ?>

    <br><br>
    <a href="view_applications.php" style="color: blue;">← Back to Applications</a>
</div>

</body>
</html>
