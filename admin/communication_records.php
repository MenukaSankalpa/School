<?php
include '../db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $role = $_SESSION['role'] ?? 0;
// if ($role != 1) {
//     die("Access denied – only super admin can view all communications.");
// }

$sql = "SELECT c.*, a.child_full_name, u.username AS admin_name
        FROM communication_logs c
        LEFT JOIN application_info a ON c.application_id = a.id
        LEFT JOIN users u ON u.username = c.sent_by
        ORDER BY c.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Communication Records</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
body {
  margin:0; font-family:'Poppins',sans-serif; background:#f8fafc;
}
.main {
  padding:40px;
}
.card {
  background:#fff;
  border-radius:10px;
  padding:25px;
  box-shadow:0 4px 16px rgba(0,0,0,0.08);
}
h1 {
  font-size:26px;
  margin-bottom:20px;
}
.table {
  width:100%;
  border-collapse:collapse;
}
.table th, .table td {
  padding:10px 12px;
  border-bottom:1px solid #e2e8f0;
  text-align:left;
}
.table th {
  background:#f1f5f9;
}
.type-email { color:#2563eb; font-weight:600; }
.type-message { color:#16a34a; font-weight:600; }
</style>
</head>
<body>
<div class="main">
<div class="card">
<h1><span class="material-icons" style="vertical-align:middle;">forum</span> Communication Records</h1>

<table class="table">
<thead>
<tr>
  <th>ID</th>
  <th>Application</th>
  <th>Type</th>
  <th>Message</th>
  <th>Sent By</th>
  <th>Time</th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($row['id']) ?></td>
  <td><?= htmlspecialchars($row['child_full_name'] ?? '-') ?></td>
  <td class="type-<?= $row['type'] ?>"><?= htmlspecialchars(ucfirst($row['type'])) ?></td>
  <td><?= htmlspecialchars($row['message']) ?></td>
  <td><?= htmlspecialchars($row['sent_by']) ?></td>
  <td><?= htmlspecialchars($row['created_at']) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</body>
</html>
