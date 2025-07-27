<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Get applications
$sql = "SELECT ai.*, u.selected_schools 
        FROM application_info ai
        LEFT JOIN users u ON ai.user_id = u.id
        WHERE ai.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Applications</title>
    <link rel="stylesheet" href="../css/va.css">
</head>
<body>
<div class="container">
    <h1>My Submitted Applications</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
            <tr>
                <th>Status</th>
                <th>Child</th>
                <th>DOB / Age</th>
                <th>Religion</th>
                <th>Applicant</th>
                <th>Spouse</th>
                <th>District</th>
                <th>Selected Schools</th>
                <th>Photos</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <span class="status pending">Pending</span>
                        <!-- You can use status column later -->
                    </td>
                    <td>
                        <?= htmlspecialchars($row['child_full_name']) ?><br>
                        <small>(<?= htmlspecialchars($row['child_initials']) ?>)</small>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['dob']) ?><br>
                        <?= htmlspecialchars($row['age']) ?> yrs
                    </td>
                    <td><?= htmlspecialchars($row['child_religion']) ?></td>
                    <td>
                        <?= htmlspecialchars($row['applicant_full_name']) ?><br>
                        <?= htmlspecialchars($row['applicant_nic']) ?><br>
                        <?= htmlspecialchars($row['applicant_phone']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['spouse_full_name']) ?><br>
                        <?= htmlspecialchars($row['spouse_nic']) ?><br>
                        <?= htmlspecialchars($row['spouse_phone']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['resident_district']) ?></td>
                    <td>
                        <?php
                        $schools = explode(',', $row['selected_schools']);
                        foreach ($schools as $school) {
                            echo "<div>" . htmlspecialchars(trim($school)) . "</div>";
                        }
                        ?>
                    </td>
                    <td>
                        <a class="view-btn" href="view_photos.php?id=<?= $row['id'] ?>" target="_blank">View Photos</a>
                    </td>
                    <td>
                        <a class="edit-btn" href="edit_application.php?id=<?= $row['id'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No applications submitted yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
