<?php
session_start();
include '../db.php';

// Allow both super admin (role = 1) and school admin (role = 2)
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['1', '2'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Handle AJAX POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $appId = intval($data['app_id'] ?? 0);

    if (!$appId) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid application ID']);
        exit;
    }

    // Fetch applicant info (super admin can view all)
    if ($_SESSION['role'] == '1') {
        $stmt = $conn->prepare("SELECT user_id, child_full_name FROM application_info WHERE id=?");
        $stmt->bind_param("i", $appId);
    } else {
        $stmt = $conn->prepare("SELECT user_id, child_full_name FROM application_info WHERE id=? AND assigned_admin_id=?");
        $stmt->bind_param("ii", $appId, $_SESSION['user_id']);
    }
    $stmt->execute();
    $app = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$app) {
        echo json_encode(['status' => 'error', 'message' => 'Applicant not found']);
        exit;
    }

    // Insert message for parent
    $stmt2 = $conn->prepare("INSERT INTO messages (user_id, message, created_at) VALUES (?, ?, NOW())");
    $msg = "Your child's application (" . $app['child_full_name'] . ") has been updated by the admin.";
    $stmt2->bind_param("is", $app['user_id'], $msg);
    if ($stmt2->execute()) {
        echo json_encode(['status'=>'success','message'=>'Message sent successfully!']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Failed to send message']);
    }
    $stmt2->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Send Message to Parent</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body { font-family:'Poppins',sans-serif; background:#f3f4f6; padding:50px; }
.container { max-width:900px; margin:0 auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.1); }
h2 { text-align:center; color:#1f2937; }
.cards-container { display:flex; flex-wrap:wrap; gap:20px; margin-top:20px; justify-content:center; }
.card { background:#fff; border-radius:12px; padding:20px; box-shadow:0 6px 20px rgba(0,0,0,0.1); flex:0 0 260px; cursor:pointer; transition: transform 0.3s, box-shadow 0.3s; }
.card:hover { transform: translateY(-5px); box-shadow:0 10px 30px rgba(0,0,0,0.15); }
.card h3 { margin:0 0 10px; color:#111827; font-size:18px; }
.card p { margin:5px 0; color:#2563eb; font-weight:500; }
.card button { margin-top:10px; width:100%; padding:8px; border:none; border-radius:6px; background:#10b981; color:#fff; cursor:pointer; transition:0.3s; }
.card button:hover { background:#059669; }
.status { font-weight:600; padding:3px 8px; border-radius:6px; font-size:14px; display:inline-block; }
.status.pending { background:#fef3c7; color:#b45309; }
.status.approved { background:#d1fae5; color:#065f46; }
#response { margin-top:15px; text-align:center; font-weight:500; }
</style>
</head>
<body>

<div class="container">
    <h2>Send Message to Parents</h2>
    <div class="cards-container">
        <?php
        // Super admin sees all applicants
        if ($_SESSION['role'] == '1') {
            $stmt = $conn->prepare("SELECT id, child_full_name, status, marks, user_id FROM application_info");
        } else {
            $stmt = $conn->prepare("SELECT id, child_full_name, status, marks, user_id FROM application_info WHERE assigned_admin_id=?");
            $stmt->bind_param("i", $_SESSION['user_id']);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()):
            $stmt2 = $conn->prepare("SELECT school_name FROM applications WHERE user_id=?");
            $stmt2->bind_param("i", $row['user_id']);
            $stmt2->execute();
            $schoolsResult = $stmt2->get_result();
            $schools = [];
            while($s = $schoolsResult->fetch_assoc()) $schools[] = $s['school_name'];
            $stmt2->close();
        ?>
        <div class="card">
            <h3><?= htmlspecialchars($row['child_full_name']) ?></h3>
            <p>Applied Schools: <?= htmlspecialchars(implode(', ', $schools)) ?></p>
            <p>Marks: <?= htmlspecialchars($row['marks'] ?? '-') ?></p>
            <span class="status <?= $row['status']=='approved'?'approved':'pending' ?>"><?= ucfirst($row['status']) ?></span>
            <button onclick="sendMessage(<?= $row['id'] ?>)">Send Message</button>
        </div>
        <?php endwhile; $stmt->close(); ?>
    </div>
    <div id="response"></div>
</div>

<script>
function sendMessage(appId){
    fetch('send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'same-origin', // keep session
        body: JSON.stringify({ app_id: appId })
    })
    .then(res => res.json())
    .then(data => {
        const respDiv = document.getElementById('response');
        respDiv.style.color = data.status==='success'?'green':'red';
        respDiv.textContent = data.message;
    })
    .catch(err => {
        console.error(err);
        const respDiv = document.getElementById('response');
        respDiv.style.color = 'red';
        respDiv.textContent = 'An unexpected error occurred';
    });
}
</script>

</body>
</html>
