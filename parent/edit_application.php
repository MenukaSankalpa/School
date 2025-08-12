<?php
session_start();
include '../db.php';


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['user_id'];


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid application ID.");
}
$app_id = (int) $_GET['id'];


$sql = "SELECT * FROM application_info WHERE id = ? AND user_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ii", $app_id, $user_id);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Application not found or access denied.");
}
$app = $result->fetch_assoc();


$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request. Please try again.';
    }

    $child_full_name      = trim($_POST['child_full_name'] ?? '');
    $dob                  = trim($_POST['dob'] ?? '');
    $age                  = (int) ($_POST['age'] ?? 0);
    $child_religion       = trim($_POST['child_religion'] ?? '');
    $applicant_full_name  = trim($_POST['applicant_full_name'] ?? '');
    $applicant_nic        = trim($_POST['applicant_nic'] ?? '');
    $applicant_phone      = trim($_POST['applicant_phone'] ?? '');
    $spouse_full_name     = trim($_POST['spouse_full_name'] ?? '');
    $spouse_nic           = trim($_POST['spouse_nic'] ?? '');
    $spouse_phone         = trim($_POST['spouse_phone'] ?? '');
    $resident_district    = trim($_POST['resident_district'] ?? '');

    if ($child_full_name === '') $errors[] = 'Child name is required.';
    if ($dob === '') $errors[] = 'DOB is required.';
    if ($age <= 0) $errors[] = 'Age must be a positive number.';

    if (empty($errors)) {
        $update_sql = "UPDATE application_info SET
            child_full_name = ?,
            dob = ?,
            age = ?,
            child_religion = ?,
            applicant_full_name = ?,
            applicant_nic = ?,
            applicant_phone = ?,
            spouse_full_name = ?,
            spouse_nic = ?,
            spouse_phone = ?,
            resident_district = ?,
            updated_at = NOW()
            WHERE id = ? AND user_id = ?";

        $update_stmt = $conn->prepare($update_sql);
        if (!$update_stmt) {
            $errors[] = 'Prepare failed: ' . $conn->error;
        } else {
            $update_stmt->bind_param(
                'ssissssssssii',
                $child_full_name,
                $dob,
                $age,
                $child_religion,
                $applicant_full_name,
                $applicant_nic,
                $applicant_phone,
                $spouse_full_name,
                $spouse_nic,
                $spouse_phone,
                $resident_district,
                $app_id,
                $user_id
            );

            if ($update_stmt->execute()) {
                $_SESSION['flash_success'] = 'Application updated successfully.';
                header('Location: view_applications.php');
                exit;
            } else {
                $errors[] = 'Update failed: ' . $update_stmt->error;
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Application</title>
    <link rel="stylesheet" href="../css/edit_application.css">
</head>
<body>
<main class="page">
    <div class="card">
        <header class="card-header">
            <h1>Edit Application</h1>
            <a class="btn-muted" href="view_applications.php">← Back to Applications</a>
        </header>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="form-grid" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <label class="form-group">
                <span class="label">Child Full Name</span>
                <input type="text" name="child_full_name" value="<?= htmlspecialchars($app['child_full_name']) ?>" required>
            </label>

            <label class="form-group">
                <span class="label">Date of Birth</span>
                <input type="date" name="dob" value="<?= htmlspecialchars($app['dob']) ?>" required>
            </label>

            <label class="form-group">
                <span class="label">Age (years)</span>
                <input type="number" name="age" min="1" value="<?= htmlspecialchars($app['age']) ?>" required>
            </label>

            <label class="form-group">
                <span class="label">Religion</span>
                <input type="text" name="child_religion" value="<?= htmlspecialchars($app['child_religion']) ?>">
            </label>

            <fieldset class="section">
                <legend>Applicant</legend>
                <label class="form-group">
                    <span class="label">Full Name</span>
                    <input type="text" name="applicant_full_name" value="<?= htmlspecialchars($app['applicant_full_name']) ?>">
                </label>
                <label class="form-group">
                    <span class="label">NIC</span>
                    <input type="text" name="applicant_nic" value="<?= htmlspecialchars($app['applicant_nic']) ?>">
                </label>
                <label class="form-group">
                    <span class="label">Phone</span>
                    <input type="tel" name="applicant_phone" value="<?= htmlspecialchars($app['applicant_phone']) ?>">
                </label>
            </fieldset>

            <fieldset class="section">
                <legend>Spouse</legend>
                <label class="form-group">
                    <span class="label">Full Name</span>
                    <input type="text" name="spouse_full_name" value="<?= htmlspecialchars($app['spouse_full_name']) ?>">
                </label>
                <label class="form-group">
                    <span class="label">NIC</span>
                    <input type="text" name="spouse_nic" value="<?= htmlspecialchars($app['spouse_nic']) ?>">
                </label>
                <label class="form-group">
                    <span class="label">Phone</span>
                    <input type="tel" name="spouse_phone" value="<?= htmlspecialchars($app['spouse_phone']) ?>">
                </label>
            </fieldset>

            <label class="form-group full-width">
                <span class="label">Resident District</span>
                <input type="text" name="resident_district" value="<?= htmlspecialchars($app['resident_district']) ?>">
            </label>

            <div class="form-actions full-width">
                <button type="submit" class="btn-primary">Save changes</button>
                <a class="btn-muted" href="view_applications.php">Cancel</a>
            </div>
        </form>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.form-grid');
    form.addEventListener('submit', function (e) {
        const age = form.querySelector('[name="age"]').value;
        if (age <= 0) {
            e.preventDefault();
            alert('Please enter a valid age.');
        }
    });
});
</script>
</body>
</html>