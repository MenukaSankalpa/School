<?php
include '../db.php';

$sql = "SELECT id, child_full_name, applicant_full_name, dob, resident_district, created_at, status 
        FROM application_info ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<h1 style="font-size: 24px; font-weight: 600; margin-bottom: 20px;">Application Submissions</h1>

<?php if ($result && $result->num_rows > 0): ?>
    <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background-color: #f1f5f9;">
                <th style="padding: 12px 16px;">ID</th>
                <th style="padding: 12px 16px;">Child Name</th>
                <th style="padding: 12px 16px;">Applicant</th>
                <th style="padding: 12px 16px;">DOB</th>
                <th style="padding: 12px 16px;">District</th>
                <th style="padding: 12px 16px;">Submitted</th>
                <th style="padding: 12px 16px;">Status</th>
                <th style="padding: 12px 16px;">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td style="padding: 12px 16px;"><?= $row['id'] ?></td>
                <td style="padding: 12px 16px;"><?= htmlspecialchars($row['child_full_name']) ?></td>
                <td style="padding: 12px 16px;"><?= htmlspecialchars($row['applicant_full_name']) ?></td>
                <td style="padding: 12px 16px;"><?= $row['dob'] ?></td>
                <td style="padding: 12px 16px;"><?= $row['resident_district'] ?></td>
                <td style="padding: 12px 16px;"><?= $row['created_at'] ?></td>
                <td style="padding: 12px 16px;">
                    <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 13px; color: white; background:
                        <?php
                            echo $row['status'] === 'approved' ? '#10b981' :
                                 ($row['status'] === 'rejected' ? '#ef4444' : '#f59e0b');
                        ?>;">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
                <td style="padding: 12px 16px;">
                    <div style="display: flex; flex-direction: column; gap: 12px; max-width: 140px;">
                        <a href="view_application.php?id=<?= $row['id'] ?>" title="View"
                           style="display: inline-flex; align-items: center; justify-content: center; 
                                  padding: 8px 14px; border-radius: 6px; border: 2px solid #3b82f6; 
                                  color: #3b82f6; font-weight: 600; font-size: 14px; 
                                  text-decoration: none; transition: all 0.3s ease; width: 100%; box-sizing: border-box;">
                            <span class="material-icons" style="font-size: 18px; margin-right: 8px;">visibility</span> View
                        </a>
                        <a href="assign_admin.php?id=<?= $row['id'] ?>" title="Approve"
                             style="display: inline-flex; align-items: center; justify-content: center; 
                                    padding: 8px 14px; border-radius: 6px; border: 2px solid #10b981; 
                                    color: #10b981; font-weight: 600; font-size: 14px; 
                                    text-decoration: none; transition: all 0.3s ease; width: 100%; box-sizing: border-box;">
                              <span class="material-icons" style="font-size: 18px; margin-right: 8px;">check_circle</span> Approve
                          </a>
                        <a href="reject_application.php?id=<?= $row['id'] ?>" title="Reject"
                           style="display: inline-flex; align-items: center; justify-content: center; 
                                  padding: 8px 14px; border-radius: 6px; border: 2px solid #ef4444; 
                                  color: #ef4444; font-weight: 600; font-size: 14px; 
                                  text-decoration: none; transition: all 0.3s ease; width: 100%; box-sizing: border-box;">
                            <span class="material-icons" style="font-size: 18px; margin-right: 8px;">cancel</span> Reject
                        </a>
                    </div>

                    <style>
                        a[title="View"]:hover {
                            background-color: #3b82f6;
                            color: white;
                            box-shadow: 0 4px 8px rgba(59,130,246,0.4);
                        }
                        a[title="View"]:hover .material-icons {
                            color: white;
                        }

                        a[title="Approve"]:hover {
                            background-color: #10b981;
                            color: white;
                            box-shadow: 0 4px 8px rgba(16,185,129,0.4);
                        }
                        a[title="Approve"]:hover .material-icons {
                            color: white;
                        }

                        a[title="Reject"]:hover {
                            background-color: #ef4444;
                            color: white;
                            box-shadow: 0 4px 8px rgba(239,68,68,0.4);
                        }
                        a[title="Reject"]:hover .material-icons {
                            color: white;
                        }
                    </style>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="margin-top: 20px; font-size: 16px;">No applications found.</p>
<?php endif; ?>
