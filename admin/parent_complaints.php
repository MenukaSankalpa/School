<?php
include '../db.php';

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_text'], $_POST['complaint_id'])) {
    $replyText = trim($_POST['reply_text']);
    $complaintId = intval($_POST['complaint_id']);

    if (!empty($replyText)) {
        $stmt = $conn->prepare("UPDATE complaints SET reply_text = ? WHERE id = ?");
        $stmt->bind_param("si", $replyText, $complaintId);
        $stmt->execute();
    }
}

// Fetch complaints
$sql = "SELECT c.id, c.complaint_text, c.created_at, c.reply_text, u.username 
        FROM complaints c 
        JOIN users u ON c.user_id = u.id 
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);
?>

<!-- Styles -->
<style>
    .complaints-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
    }

    .complaints-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 24px;
        color: #1f2937;
        border-left: 4px solid #3b82f6;
        padding-left: 12px;
    }

    table.complaints-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    table.complaints-table th, 
    table.complaints-table td {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
    }

    table.complaints-table thead {
        background-color: #f3f4f6;
        text-align: left;
    }

    table.complaints-table th {
        color: #374151;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
    }

    table.complaints-table td {
        color: #4b5563;
    }

    .reply-form textarea {
        width: 100%;
        min-height: 80px;
        margin-top: 10px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-family: inherit;
        resize: vertical;
    }

    .reply-form button {
        margin-top: 10px;
        background-color: #2563eb;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
    }

    .reply-form button:hover {
        background-color: #1d4ed8;
    }

    .reply-box {
        background: #f9fafb;
        margin-top: 8px;
        padding: 12px;
        border-left: 3px solid #10b981;
        border-radius: 6px;
        font-size: 14px;
        color: #065f46;
    }

    @media (max-width: 768px) {
        .complaints-container {
            padding: 20px 15px;
        }

        table.complaints-table th, 
        table.complaints-table td {
            padding: 12px 10px;
        }
    }
</style>

<!-- HTML Content -->
<div class="complaints-container">
    <h2 class="complaints-title">Parent Complaints</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="complaints-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Complaint</th>
                    <th>Submitted At</th>
                    <th>Reply</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['complaint_text'])) ?></td>
                        <td><?= date("d M Y, H:i", strtotime($row['created_at'])) ?></td>
                        <td>
                            <?php if (!empty($row['reply_text'])): ?>
                                <div class="reply-box"><?= nl2br(htmlspecialchars($row['reply_text'])) ?></div>
                            <?php else: ?>
                                <form method="POST" class="reply-form">
                                    <textarea name="reply_text" placeholder="Write a reply..." required></textarea>
                                    <input type="hidden" name="complaint_id" value="<?= $row['id'] ?>">
                                    <button type="submit">Send Reply</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color: #9ca3af;">No complaints found.</p>
    <?php endif; ?>
</div>
