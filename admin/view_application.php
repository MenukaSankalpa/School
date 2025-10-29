<?php
session_start();
include '../db.php'; // your database connection

// Only school admin can access
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Handle message sending
if (isset($_POST['send_message'])) {
    $application_id = $_POST['application_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $sender = 'admin';
    $receiver = 'parent';

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (applicant_id, sender, receiver, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $application_id, $sender, $receiver, $subject, $message);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $success = "Message sent successfully!";
        } else {
            $error = "Failed to send message.";
        }
        $stmt->close();
    } else {
        $error = "Message cannot be empty.";
    }
}

// Fetch all applications
$applications = $conn->query("SELECT * FROM application_info ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Applications</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<h1>Applications</h1>

<?php
if (isset($success)) echo "<p style='color:green;'>$success</p>";
if (isset($error)) echo "<p style='color:red;'>$error</p>";
?>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Applicant Name</th>
            <th>Email</th>
            <th>Child Name</th>
            <th>Marks</th>
            <th>Feedback</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $applications->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['applicant_full_name']; ?></td>
            <td><?php echo $row['applicant_phone']; ?></td>
            <td><?php echo $row['child_full_name']; ?></td>
            <td><?php echo $row['marks']; ?></td>
            <td>
                <form method="post" action="update_feedback.php">
                    <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                    <textarea name="feedback" rows="2" cols="20"><?php echo $row['feedback']; ?></textarea>
                    <button type="submit" name="update_feedback">Update</button>
                </form>
            </td>
            <td>
                <form method="post">
                    <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="subject" placeholder="Subject" required>
                    <textarea name="message" placeholder="Message to parent" required></textarea>
                    <button type="submit" name="send_message">Send Message</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>
