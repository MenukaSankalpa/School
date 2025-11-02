<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '2') {
    header("Location: ../index.html");
    exit;
}

$adminId = $_SESSION['user_id'];
$applicantId = $_GET['id'] ?? 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marks'])) {
    $marks = $_POST['marks'] ?? null;
    $feedback = $_POST['feedback'] ?? null;
    $status = $_POST['status'] ?? 'pending';

    $update = $conn->prepare("UPDATE application_info SET marks=?, feedback=?, status=? WHERE id=? AND assigned_admin_id=?");
    $update->bind_param("issii", $marks, $feedback, $status, $applicantId, $adminId);
    $update->execute();
    $update->close();

    header("Location: view_applicant.php?id=$applicantId");
    exit;
}

// Fetch applicant details
$stmt = $conn->prepare("SELECT * FROM application_info WHERE id=? AND assigned_admin_id=?");
$stmt->bind_param("ii", $applicantId, $adminId);
$stmt->execute();
$result = $stmt->get_result();
$applicant = $result->fetch_assoc();
$stmt->close();

if (!$applicant) {
    echo "Applicant not found!";
    exit;
}

$userId = $applicant['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Applicant Details</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f3f4f6;
    padding: 40px;
}
.card {
    max-width: 850px;
    margin: 0 auto;
    background: #fff;
    border-radius: 15px;
    padding: 30px 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
h1 {
    margin-top: 0;
    font-size: 24px;
    color: #1f2937;
}
h3 {
    margin-top: 25px;
    color: #111827;
    font-size: 18px;
}
.image-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 15px;
}
.image-gallery img {
    width: 180px;
    height: 130px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #e5e7eb;
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
}
.image-gallery img:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}
.no-image {
    color: #6b7280;
    font-style: italic;
    font-size: 14px;
}
.card table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.card table td {
    padding: 8px 10px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
}
form label {
    font-weight: 500;
    display: block;
    margin-top: 15px;
    margin-bottom: 5px;
    color: #374151;
}
form input[type="number"],
form textarea,
form select {
    width: 100%;
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-family: inherit;
}
form button {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 500;
    transition: background 0.3s;
}
form button:hover {
    background: #1e40af;
}
</style>
</head>
<body>

<div class="card">
    <h1><?= htmlspecialchars($applicant['child_full_name']) ?> 
        <small>(<?= htmlspecialchars($applicant['dob']) ?>)</small>
    </h1>

    <h3>Uploaded Electricity Bills</h3>
    <div class="image-gallery">
        <?php
        $ebillPath = "../uploads/ebills/$applicantId/";
$lbillPath = "../uploads/lbills/$applicantId/";

        $hasImage = false;

        function showFolderImages($folder) {
            global $hasImage;
            if (is_dir($folder)) {
                $files = glob($folder . "*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
                foreach ($files as $file) {
                    $fileUrl = $file;
                    echo '<a href="' . $fileUrl . '" target="_blank">
                            <img src="' . $fileUrl . '" alt="Uploaded Bill">
                          </a>';
                    $hasImage = true;
                }
            }
        }

        showFolderImages($ebillPath);

        if (!$hasImage) echo '<p class="no-image">No electricity bills uploaded.</p>';
        ?>
    </div>

    <h3>Uploaded Location Bills</h3>
    <div class="image-gallery">
        <?php
        $hasImage = false;
        showFolderImages($lbillPath);
        if (!$hasImage) echo '<p class="no-image">No location bills uploaded.</p>';
        ?>
    </div>

    <h3>Applicant Details</h3>
    <table>
        <?php
        foreach ($applicant as $key => $value) {
            if (in_array($key, ['id','assigned_admin_id','ebill_files','lbill_files','marks','feedback','status','created_at'])) continue;
            echo '<tr><td style="width:30%; font-weight:500;">' . ucwords(str_replace('_', ' ', $key)) . '</td>
                      <td>' . htmlspecialchars($value) . '</td></tr>';
        }
        ?>
    </table>

    <h3>Update Marks & Feedback</h3>
    <form method="POST">
        <label>Marks</label>
        <input type="number" name="marks" min="0" max="100" value="<?= htmlspecialchars($applicant['marks'] ?? '') ?>">

        <label>Feedback</label>
        <textarea name="feedback" rows="4"><?= htmlspecialchars($applicant['feedback'] ?? '') ?></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending" <?= $applicant['status']=='pending'?'selected':'' ?>>Pending</option>
            <option value="approved" <?= $applicant['status']=='approved'?'selected':'' ?>>Approved</option>
        </select>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
