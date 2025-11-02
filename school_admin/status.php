<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '2') {
    header("Location: ../index.html");
    exit;
}

$adminId = $_SESSION['user_id'];

$sql = "SELECT a.id, a.child_full_name, a.applicant_full_name, a.status, a.marks, a.feedback, a.ebill_files, a.lbill_files, a.created_at
        FROM application_info a
        WHERE a.assigned_admin_id = ?
        ORDER BY a.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
$applications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

function parseFileList($fileList) {
    if (!$fileList || trim($fileList) === '') return [];
    return array_filter(array_map('trim', explode(',', $fileList)));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Application Status</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f3f4f6; margin:0; padding:0;}
.container { max-width: 1200px; margin: 40px auto; background: #fff; padding: 20px 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);}
h1 { text-align: center; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; font-size: 14px; vertical-align: middle; }
th { background: #f1f5f9; }
.status-pending { color: #d97706; font-weight: 600; }
.status-approved { color: #16a34a; font-weight: 600; }
.status-rejected { color: #dc2626; font-weight: 600; }
.img-thumb { width: 80px; height: auto; border-radius: 5px; border: 1px solid #ccc; }
.btn {
    padding: 6px 12px;
    margin: 2px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: 0.3s;
    display: inline-block;
}
.btn-view {
    background: linear-gradient(90deg, #2563eb, #1d4ed8);
    color: #fff;
}
.btn-view:hover { background: linear-gradient(90deg, #1e40af, #1e3a8a); }
.btn-delete {
    background: linear-gradient(90deg, #ef4444, #dc2626);
    color: #fff;
}
.btn-delete:hover { background: linear-gradient(90deg, #b91c1c, #991b1b); }
</style>
</head>
<body>

<div class="container">
<h1>Application Status</h1>

<?php if (!empty($applications)): ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Child Name</th>
            <th>Applicant Name</th>
            <th>Status</th>
            <th>Marks</th>
            <th>Feedback</th>
            <th>E-Bill</th>
            <th>L-Bill</th>
            <th>Submitted At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($applications as $app): ?>
            <tr>
                <td><?= htmlspecialchars($app['id']) ?></td>
                <td><?= htmlspecialchars($app['child_full_name']) ?></td>
                <td><?= htmlspecialchars($app['applicant_full_name']) ?></td>
                <td class="status-<?= strtolower($app['status']) ?>"><?= htmlspecialchars(ucfirst($app['status'])) ?></td>
                <td><?= htmlspecialchars($app['marks'] ?? '-') ?></td>
                <td><?= htmlspecialchars($app['feedback'] ?? '-') ?></td>
                <td>
                    <?php 
                    $ebills = parseFileList($app['ebill_files']);
                    if (!empty($ebills)):
                        foreach ($ebills as $file):
                            if (file_exists("../$file")):
                    ?>
                        <a href="../<?= htmlspecialchars($file) ?>" target="_blank"><img src="../<?= htmlspecialchars($file) ?>" class="img-thumb" alt="E-Bill"></a>
                    <?php 
                            endif;
                        endforeach; 
                    else: 
                        echo "-";
                    endif; 
                    ?>
                </td>
                <td>
                    <?php 
                    $lbills = parseFileList($app['lbill_files']);
                    if (!empty($lbills)):
                        foreach ($lbills as $file):
                            if (file_exists("../$file")):
                    ?>
                        <a href="../<?= htmlspecialchars($file) ?>" target="_blank"><img src="../<?= htmlspecialchars($file) ?>" class="img-thumb" alt="L-Bill"></a>
                    <?php 
                            endif;
                        endforeach; 
                    else: 
                        echo "-";
                    endif; 
                    ?>
                </td>
                <td><?= htmlspecialchars(date("d M Y, h:i A", strtotime($app['created_at']))) ?></td>
                <td>
                    <a class="btn btn-view" href="view_applicant.php?id=<?= $app['id'] ?>">View / Edit</a>
                    <!-- <a class="btn btn-delete" href="delete_application.php?id=<?= $app['id'] ?>" onclick="return confirm('Are you sure you want to delete this application?')">Delete</a> -->
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p style="text-align:center; padding:20px;">No applications assigned yet.</p>
<?php endif; ?>

</div>

</body>
</html>
