<?php
session_start();
include '../db.php';

// Handle message sending
if (isset($_POST['send_message'])) {
    $application_id = $_POST['application_id'];
    $message = $_POST['message'];
    $sender = 'admin';
    $receiver = 'parent';
    $subject = ''; // No subject field

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (applicant_id, sender, receiver, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $application_id, $sender, $receiver, $subject, $message);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $success = "✅ Message sent successfully!";
        } else {
            $error = "❌ Failed to send message.";
        }
        $stmt->close();
    } else {
        $error = "⚠️ Message cannot be empty.";
    }
}

// Fetch applications + email
$applications = $conn->query("
    SELECT a.*, u.email 
    FROM application_info a
    LEFT JOIN users u ON a.user_id = u.id
    ORDER BY a.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Applications Dashboard</title>
<style>
    :root {
        --blue: #3b82f6;
        --blue-dark: #2563eb;
        --green: #10b981;
        --danger: #ef4444;
        --bg: #f1f5f9;
        --card-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.4);
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: "Inter", "Poppins", sans-serif;
        background: linear-gradient(135deg, #e0e7ff, #f8fafc);
        margin: 0;
        padding: 40px;
        color: #1e293b;
        backdrop-filter: blur(20px);
    }

    h1 {
        text-align: center;
        color: #1e293b;
        margin-bottom: 35px;
        font-size: 2.2em;
        font-weight: 600;
        letter-spacing: -0.5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 16px;
        overflow: hidden;
        background: var(--card-bg);
        backdrop-filter: blur(20px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    thead {
        background: linear-gradient(90deg, var(--blue), var(--blue-dark));
        color: #fff;
    }

    th, td {
        padding: 18px 16px;
        text-align: left;
        font-size: 15px;
    }

    th {
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tr:nth-child(even) {
        background: rgba(249, 250, 251, 0.8);
    }

    tr:hover {
        background: rgba(219, 234, 254, 0.8);
        transition: background 0.3s ease;
    }

    /* Cards for forms */
    td form {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 16px;
        border-radius: 12px;
        background: rgba(255,255,255,0.7);
        border: 1px solid var(--glass-border);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
    }

    td form:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: rgba(255,255,255,0.9);
        font-size: 14px;
        resize: none;
        transition: all 0.3s;
    }

    textarea:focus {
        border-color: var(--blue);
        outline: none;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
    }

    textarea[name="feedback"] {
        height: 80px;
    }

    textarea[name="message"] {
        height: 90px;
    }

    button {
        background: linear-gradient(90deg, var(--blue), var(--blue-dark));
        color: #fff;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        align-self: flex-end;
        box-shadow: 0 4px 10px rgba(59,130,246,0.25);
    }

    button:hover {
        transform: translateY(-2px);
        background: linear-gradient(90deg, var(--blue-dark), #1e40af);
        box-shadow: 0 6px 14px rgba(30,64,175,0.3);
    }

    /* Toast Notification */
    .toast {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background: rgba(255,255,255,0.95);
        border-radius: 10px;
        padding: 14px 20px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        font-size: 15px;
        font-weight: 500;
        color: #1e293b;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.4s ease;
        border-left: 5px solid;
        z-index: 1000;
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    .toast.success {
        border-color: var(--green);
    }

    .toast.error {
        border-color: var(--danger);
    }
</style>
</head>
<body>

<h1>Applications Dashboard</h1>

<?php if (isset($success)) echo "<div class='toast success show'>$success</div>"; ?>
<?php if (isset($error)) echo "<div class='toast error show'>$error</div>"; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Applicant</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Child</th>
            <th>Marks</th>
            <th>Feedback</th>
            <th>Message</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $applications->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['applicant_full_name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['applicant_phone']); ?></td>
            <td><?php echo htmlspecialchars($row['child_full_name']); ?></td>
            <td><?php echo htmlspecialchars($row['marks']); ?></td>
            <td>
                <form method="post" action="update_feedback.php">
                    <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                    <textarea name="feedback" placeholder="Enter feedback..."><?php echo htmlspecialchars($row['feedback']); ?></textarea>
                    <button type="submit" name="update_feedback">💾 Save</button>
                </form>
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                    <textarea name="message" placeholder="Write a message to parent..." required></textarea>
                    <button type="submit" name="send_message">📨 Send</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
    // Auto-hide toast
    const toast = document.querySelector('.toast');
    if (toast) {
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 600);
        }, 3000);
    }
</script>

</body>
</html>
