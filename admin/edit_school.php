<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../db.php';

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid school ID.");
}

$school_id = intval($_GET['id']);
$error_message = $success_message = "";

// Fetch school
$stmt = $conn->prepare("SELECT * FROM schools WHERE id = ?");
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("School not found.");
}
$school = $result->fetch_assoc();

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update_school') {
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $address = trim($_POST['address']);
    $latitude = floatval($_POST['latitude']);
    $longitude = floatval($_POST['longitude']);

    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        $error_message = "Invalid latitude or longitude values.";
    } else {
        $updateStmt = $conn->prepare("UPDATE schools SET name = ?, type = ?, address = ?, latitude = ?, longitude = ? WHERE id = ?");
        $updateStmt->bind_param("sssddi", $name, $type, $address, $latitude, $longitude, $school_id);

        if ($updateStmt->execute()) {
            $success_message = "School updated successfully!";
            $school = ['name' => $name, 'type' => $type, 'address' => $address, 'latitude' => $latitude, 'longitude' => $longitude];
        } else {
            $error_message = "Error updating school: " . htmlspecialchars($updateStmt->error);
        }
    }
}
?>

<div style="max-width:600px;margin:auto;">
    <h2>Edit School</h2>

    <?php if (!empty($error_message)): ?>
        <div style="background:#f8d7da;color:#721c24;padding:10px;border-radius:5px;margin-bottom:10px;">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div style="background:#d4edda;color:#155724;padding:10px;border-radius:5px;margin-bottom:10px;">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.05);">
        <input type="hidden" name="action" value="update_school">

        <input type="text" name="name" placeholder="School Name" value="<?= htmlspecialchars($school['name']) ?>" required style="width:100%;margin-bottom:10px;padding:10px;">
        
        <select name="type" required style="width:100%;margin-bottom:10px;padding:10px;">
            <option value="">Select Type</option>
            <option value="boy" <?= $school['type'] === 'boy' ? 'selected' : '' ?>>Boy</option>
            <option value="girl" <?= $school['type'] === 'girl' ? 'selected' : '' ?>>Girl</option>
            <option value="mixed" <?= $school['type'] === 'mixed' ? 'selected' : '' ?>>Mixed</option>
        </select>

        <input type="text" name="address" placeholder="Address" value="<?= htmlspecialchars($school['address']) ?>" required style="width:100%;margin-bottom:10px;padding:10px;">
        <input type="text" name="latitude" placeholder="Latitude" value="<?= htmlspecialchars($school['latitude']) ?>" required style="width:100%;margin-bottom:10px;padding:10px;">
        <input type="text" name="longitude" placeholder="Longitude" value="<?= htmlspecialchars($school['longitude']) ?>" required style="width:100%;margin-bottom:10px;padding:10px;">

        <button type="submit" style="background:#007bff;color:#fff;padding:10px 20px;border:none;border-radius:5px;">Update School</button>
        <a href="../admin/layout.php?page=view_schools" style="margin-left:15px;text-decoration:none;color:#007bff;">← Back</a>
    </form>
</div>
