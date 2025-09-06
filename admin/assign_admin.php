<?php
include '../db.php';

// Fetch applications
$sql = "SELECT a.id, a.child_full_name, a.applicant_full_name, a.dob, 
               a.resident_district, a.created_at, a.status, 
               a.assigned_admin_id, u.username AS assigned_admin
        FROM application_info a
        LEFT JOIN users u ON a.assigned_admin_id = u.id
        ORDER BY a.created_at DESC";
$result = $conn->query($sql);

// Fetch admins (role=2)
$adminSql = "SELECT id, username FROM users WHERE role = 2";
$admins = $conn->query($adminSql);
$adminList = [];
while ($row = $admins->fetch_assoc()) {
    $adminList[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Submissions</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; margin: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        th, td { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; text-align: left; }
        th { background: #f1f5f9; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 8px 14px; border-radius: 6px; font-size: 14px; font-weight: 600; text-decoration: none; transition: 0.3s; cursor: pointer; }
        .btn-view { border: 2px solid #3b82f6; color: #3b82f6; }
        .btn-approve { border: 2px solid #10b981; color: #10b981; }
        .btn-reject { border: 2px solid #ef4444; color: #ef4444; }
        .btn-view:hover { background: #3b82f6; color: #fff; }
        .btn-approve:hover { background: #10b981; color: #fff; }
        .btn-reject:hover { background: #ef4444; color: #fff; }

        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                 background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); 
                 justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: #fff; padding: 25px; border-radius: 12px; width: 400px; position: relative; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .modal h2 { margin-bottom: 20px; font-size: 20px; color: #111827; }
        .modal label { font-weight: 600; display: block; margin-bottom: 8px; }
        .modal select, .modal button { width: 100%; padding: 12px; font-size: 15px; border-radius: 8px; border: 1px solid #d1d5db; margin-bottom: 15px; }
        .modal button { background: #2563eb; color: white; border: none; font-weight: 600; cursor: pointer; }
        .modal button:hover { background: #1e40af; }
        .close-btn { position: absolute; top: 10px; right: 15px; font-size: 20px; cursor: pointer; color: #555; }
        .close-btn:hover { color: #000; }
    </style>
</head>
<body>
    <h1 style="font-size: 24px; font-weight: 600; margin-bottom: 20px;">Application Submissions</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Child Name</th>
                    <th>Applicant</th>
                    <th>DOB</th>
                    <th>District</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Assigned Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['child_full_name']) ?></td>
                    <td><?= htmlspecialchars($row['applicant_full_name']) ?></td>
                    <td><?= $row['dob'] ?></td>
                    <td><?= $row['resident_district'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <span style="padding: 4px 10px; border-radius: 12px; font-size: 13px; color: #fff; background:
                            <?= $row['status'] === 'approved' ? '#10b981' :
                               ($row['status'] === 'rejected' ? '#ef4444' : '#f59e0b'); ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td><?= $row['assigned_admin'] ? htmlspecialchars($row['assigned_admin']) : '-' ?></td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 8px; max-width: 140px;">
                            <a href="view_application.php?id=<?= $row['id'] ?>" class="btn btn-view">View</a>
                            <button class="btn btn-approve" onclick="openModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['child_full_name']) ?>')">Approve</button>
                            <a href="reject_application.php?id=<?= $row['id'] ?>" class="btn btn-reject">Reject</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No applications found.</p>
    <?php endif; ?>

    <!-- Modal -->
    <div id="assignModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Assign Admin</h2>
            <form method="POST" action="process_assign.php">
                <input type="hidden" name="app_id" id="modalAppId">

                <label for="admin_id">Select Admin</label>
                <select name="admin_id" id="admin_id" required>
                    <option value="">-- Select Admin --</option>
                    <?php foreach ($adminList as $admin): ?>
                        <option value="<?= $admin['id'] ?>"><?= htmlspecialchars($admin['username']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Assign Admin</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(appId, childName) {
            document.getElementById('assignModal').style.display = 'flex';
            document.getElementById('modalAppId').value = appId;
            document.getElementById('modalTitle').innerText = "Assign Admin to: " + childName;
        }
        function closeModal() {
            document.getElementById('assignModal').style.display = 'none';
        }
    </script>
</body>
</html>
