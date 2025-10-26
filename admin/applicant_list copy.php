<?php
include '../db.php';

// Fetch applications
$sql = "SELECT a.id, a.child_full_name, a.applicant_full_name, a.dob,
                a.resident_district, a.created_at, a.status,
                a.assigned_admin_id, ad.username AS assigned_admin
        FROM application_info a
        LEFT JOIN admins ad ON a.assigned_admin_id = ad.id
        ORDER BY a.created_at DESC";
$result = $conn->query($sql);

// Fetch all admins
$adminSql = "SELECT id, username FROM admins";
$admins = $conn->query($adminSql);
$adminList = [];
while ($row = $admins->fetch_assoc()) {
    $adminList[] = $row;
}
?>

<h1 style="font-size: 24px; font-weight: 600; margin-bottom: 20px;">Application Submissions</h1>

<?php if ($result && $result->num_rows > 0): ?>
    <table class="application-table">
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
                    <span class="status <?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span>
                </td>
                <td><?= $row['assigned_admin'] ? htmlspecialchars($row['assigned_admin']) : '-' ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="view_application.php?id=<?= $row['id'] ?>" class="btn btn-view">View</a>
                        <button class="btn btn-approve" onclick="openModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['child_full_name']) ?>')">Assign Admin</button>
                        <button class="btn btn-reject" onclick="openRejectModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['child_full_name']) ?>')">Reject</button>
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
            <div class="custom-select-wrapper">
                <select name="admin_id" id="admin_id" required>
                    <option value="">-- Select Admin --</option>
                    <?php foreach ($adminList as $admin): ?>
                        <option value="<?= $admin['id'] ?>"><?= htmlspecialchars($admin['username']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-submit">Assign Admin</button>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeRejectModal()">&times;</span>
        <h2 id="rejectModalTitle">Reject Application</h2>
        <p>Are you sure you want to reject this application?</p>
        <form method="POST" action="process_reject.php">
            <input type="hidden" name="app_id" id="rejectAppId">
            <button type="submit" class="btn btn-submit-reject">Yes, Reject</button>
            <button type="button" class="btn btn-cancel" onclick="closeRejectModal()">Cancel</button>
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

<script>
function openRejectModal(appId, childName) {
    document.getElementById('rejectModal').style.display = 'flex';
    document.getElementById('rejectAppId').value = appId;
    document.getElementById('rejectModalTitle').innerText = "Reject Application: " + childName;
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>


<style>
/* Table Styling */
.application-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.application-table th, .application-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    text-align: left;
}
.application-table th {
    background: #f1f5f9;
}

/* Status Labels */
.status.approved { background: #10b981; color: #fff; padding: 4px 10px; border-radius: 12px; font-size: 13px; }
.status.rejected { background: #ef4444; color: #fff; padding: 4px 10px; border-radius: 12px; font-size: 13px; }
.status.pending { background: #f59e0b; color: #fff; padding: 4px 10px; border-radius: 12px; font-size: 13px; }

/* Buttons */
.action-buttons { display: flex; flex-direction: column; gap: 6px; max-width: 140px; }
.btn { padding: 6px 12px; border-radius: 6px; cursor: pointer; text-align: center; font-weight: 600; border: 2px solid; }
.btn-view { border-color: #3b82f6; color: #3b82f6; }
.btn-approve { border-color: #10b981; color: #10b981; }
.btn-reject { border-color: #ef4444; color: #ef4444; }
.btn-view:hover { background: #3b82f6; color: #fff; }
.btn-approve:hover { background: #10b981; color: #fff; }
.btn-reject:hover { background: #ef4444; color: #fff; }

/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
    justify-content:center; align-items:center;
    z-index:1000;
}
.modal-content {
    background:#fff;
    padding:25px;
    border-radius:12px;
    width:400px;
    position:relative;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}
.close-btn {
    position:absolute;
    top:10px; right:15px;
    font-size:20px;
    cursor:pointer;
    color:#555;
}
.close-btn:hover { color:#000; }


/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
    justify-content:center; align-items:center;
    z-index:1000;
}
.modal-content {
    background:#fff;
    padding:25px;
    border-radius:12px;
    width:400px;
    max-width:90%;
    position:relative;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    text-align: center;
}
.close-btn {
    position:absolute;
    top:10px; right:15px;
    font-size:20px;
    cursor:pointer;
    color:#555;
}
.close-btn:hover { color:#000; }

/* Buttons */
.btn-submit-reject {
    background: #ef4444;
    color: #fff;
    border: none;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    margin-right: 10px;
    transition: 0.3s;
}
.btn-submit-reject:hover { background: #b91c1c; }

.btn-cancel {
    background: #9ca3af;
    color: #fff;
    border: none;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}
.btn-cancel:hover { background: #6b7280; }


/* Modern Select */
.custom-select-wrapper {
    position: relative;
    width: 100%;
    margin-bottom: 20px;
}
.custom-select-wrapper select {
    appearance: none;
    width: 100%;
    padding: 12px 40px 12px 15px;
    font-size: 15px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
    cursor: pointer;
    transition: 0.3s;
}
.custom-select-wrapper select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.2);
    outline: none;
}
.custom-select-wrapper::after {
    content: '▼';
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    font-size: 12px;
    color: #555;
}

/* Submit Button */
.btn-submit {
    background: #2563eb;
    color: #fff;
    border: none;
    font-weight: 600;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}
.btn-submit:hover { background: #1e40af; }
</style>
