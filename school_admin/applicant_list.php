<?php
session_start();
include '../db.php';

// Only for school admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '2') {
    header("Location: ../index.html");
    exit;
}

$adminId = $_SESSION['user_id'];

// Fetch applicants assigned to this admin
$sql = "SELECT ai.id, ai.child_full_name, ai.status, ai.marks, ai.feedback, ai.user_id
        FROM application_info ai
        WHERE ai.assigned_admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Applicants</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: #f3f4f6;
}
.main-content {
    margin-left: 240px;
    padding: 40px;
}
h1 {
    font-size: 26px;
    margin-bottom: 30px;
    color: #1f2937;
}
.cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
}
.card {
    background: #fff;
    border-radius: 18px;
    padding: 22px 24px;
    width: 290px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}
.card h3 {
    font-size: 18px;
    color: #111827;
    margin-bottom: 8px;
}
.schools {
    margin-bottom: 14px;
    padding-left: 10px;
}
.school {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #2563eb;
    font-size: 14px;
    margin-bottom: 4px;
}
.school i {
    color: #3b82f6;
}
.label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-top: 10px;
    margin-bottom: 4px;
    display: block;
}
.marks-box input {
    width: 80px;
    padding: 6px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-weight: 600;
    text-align: center;
    font-size: 14px;
    transition: border 0.2s;
}
.marks-box input:focus {
    outline: none;
    border-color: #2563eb;
}
.feedback-box {
    width: 100%;
    resize: none;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    padding: 8px;
    font-size: 13px;
    height: 55px;
    transition: border 0.2s;
}
.feedback-box:focus {
    outline: none;
    border-color: #2563eb;
}
.save-btn {
    background: #2563eb;
    color: white;
    border: none;
    padding: 7px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    margin-top: 8px;
    transition: background 0.3s;
}
.save-btn:hover {
    background: #1e40af;
}
.status {
    position: absolute;
    top: 15px;
    right: 20px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
}
.status.pending {
    background: #fef3c7;
    color: #b45309;
}
.status.approved {
    background: #d1fae5;
    color: #065f46;
}
</style>

<script>
function openApplicant(id){
    window.open('view_applicant.php?id='+id, '_blank', 'width=900,height=700,scrollbars=yes');
}

function saveFeedback(id){
    const marks = document.getElementById('marks-'+id).value;
    const feedback = document.getElementById('feedback-'+id).value;

    fetch('update_feedback.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}&marks=${marks}&feedback=${encodeURIComponent(feedback)}`
    })
    .then(res => res.text())
    .then(data => alert('✅ Feedback updated successfully!'));
}
</script>
</head>
<body>

<?php include 'layout.php'; ?>

<div class="main-content">
    <h1>Applicants</h1>
    <div class="cards-container">
        <?php while($row = $result->fetch_assoc()): ?>
            <?php
            // Fetch applied schools
            $stmt2 = $conn->prepare("SELECT school_name FROM applications WHERE user_id = ?");
            $stmt2->bind_param("i", $row['user_id']);
            $stmt2->execute();
            $schoolsResult = $stmt2->get_result();
            $schools = [];
            while($s = $schoolsResult->fetch_assoc()){
                $schools[] = $s['school_name'];
            }
            $stmt2->close();
            ?>
            <div class="card" onclick="openApplicant(<?= $row['id'] ?>)">
                <h3><?= htmlspecialchars($row['child_full_name']) ?></h3>
                <div class="schools">
                    <?php foreach($schools as $school): ?>
                        <div class="school"><i class="fas fa-school"></i> <?= htmlspecialchars($school) ?></div>
                    <?php endforeach; ?>
                </div>

                <label class="label">Mark</label>
                <div class="marks-box">
                    <input type="number" id="marks-<?= $row['id'] ?>" value="<?= htmlspecialchars($row['marks'] ?? '') ?>" placeholder="0">
                </div>

                <label class="label">Feedback</label>
                <textarea id="feedback-<?= $row['id'] ?>" class="feedback-box" placeholder="Enter feedback..."><?= htmlspecialchars($row['feedback'] ?? '') ?></textarea>

                <button class="save-btn" onclick="event.stopPropagation(); saveFeedback(<?= $row['id'] ?>)">Save</button>

                <span class="status <?= $row['status']=='approved'?'approved':'pending' ?>"><?= ucfirst($row['status']) ?></span>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
