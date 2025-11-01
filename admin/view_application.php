<?php
session_start();
// Assuming '../db.php' contains your database connection logic ($conn)
include '../db.php'; 

// --- Database Message Sending Handler ---
if (isset($_POST['send_message_db'])) {
    $application_id = $_POST['application_id'];
    // The message is retrieved from the PHP form's POST data
    $message = $_POST['message_db']; 
    $sender = 'admin';
    $receiver = 'parent'; // or applicant
    $subject = ''; // Subject is empty as in original code

    if (!empty($message)) {
        // Use the application_id (which is the applicant_id in messages table)
        $stmt = $conn->prepare("INSERT INTO messages (applicant_id, sender, receiver, subject, message) VALUES (?, ?, ?, ?, ?)");
        // "issss" for integer, string, string, string, string
        $stmt->bind_param("issss", $application_id, $sender, $receiver, $subject, $message);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            // Set success message for the toast notification
            $success = "✅ Message saved to DB successfully!";
        } else {
            $error = "❌ Failed to save message to DB.";
        }
        $stmt->close();
    } else {
        $error = "⚠️ Message cannot be empty.";
    }
}
// ----------------------------------------

// --- Fetch applications + applicant email ---
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
<script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>
<script>
    // **REPLACE "YOUR_EMAILJS_USER_ID" with your actual EmailJS Public Key**
    emailjs.init("6asBvFmjIxXAvCHCY"); // Use the ID from your original code
</script>

<style>
/* --- STYLES (Kept as provided) --- */
:root {
    --blue: #3b82f6;
    --blue-dark: #2563eb;
    --green: #10b981;
    --danger: #ef4444;
    --bg: #f1f5f9;
    --card-bg: rgba(255, 255, 255, 0.85);
    --glass-border: rgba(255, 255, 255, 0.4);
}
* { box-sizing: border-box; }
body {
    font-family: "Inter", "Poppins", sans-serif;
    background: linear-gradient(135deg, #e0e7ff, #f8fafc);
    margin: 0; padding: 40px; color: #1e293b;
}
h1 { text-align: center; color: #1e293b; margin-bottom: 35px; font-size: 2.2em; }
table {
    width: 100%; border-collapse: collapse; border-radius: 16px; overflow: hidden;
    background: var(--card-bg); backdrop-filter: blur(20px); box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
thead { background: linear-gradient(90deg, var(--blue), var(--blue-dark)); color: #fff; }
th, td { padding: 18px 16px; text-align: left; font-size: 15px; }
th { text-transform: uppercase; letter-spacing: 0.5px; }
tr:nth-child(even) { background: rgba(249, 250, 251, 0.8); }
tr:hover { background: rgba(219, 234, 254, 0.8); transition: background 0.3s ease; }
td form { display: flex; flex-direction: column; gap: 12px; padding: 16px; border-radius: 12px;
    background: rgba(255,255,255,0.7); border: 1px solid var(--glass-border); box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    transition: all 0.3s ease; position: relative;
}
td form:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,0.08); }
textarea { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 8px;
    background: rgba(255,255,255,0.9); font-size: 14px; resize: none; transition: all 0.3s; }
textarea:focus { border-color: var(--blue); outline: none; background: #fff; box-shadow: 0 0 0 3px rgba(59,130,246,0.2); }
textarea[name="feedback"] { height: 80px; }
textarea[name="message_db"], textarea[name="message_email"] { height: 90px; }
button {
    background: linear-gradient(90deg, var(--blue), var(--blue-dark)); color: #fff;
    border: none; padding: 10px 16px; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;
    align-self: flex-end; box-shadow: 0 4px 10px rgba(59,130,246,0.25);
}
button:hover { transform: translateY(-2px); background: linear-gradient(90deg, var(--blue-dark), #1e40af); box-shadow: 0 6px 14px rgba(30,64,175,0.3); }

/* Toast */
.toast { position: fixed; bottom: 25px; right: 25px; background: rgba(255,255,255,0.95); border-radius: 10px;
    padding: 14px 20px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); font-size: 15px; font-weight: 500; color: #1e293b;
    opacity: 0; transform: translateY(20px); transition: all 0.4s ease; border-left: 5px solid; z-index: 1000; }
.toast.show { opacity: 1; transform: translateY(0); }
.toast.success { border-color: var(--green); }
.toast.error { border-color: var(--danger); }
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
        <form id="message-form-<?php echo $row['id']; ?>" method="post" action="">
            <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="recipient_email" value="<?php echo htmlspecialchars($row['email']); ?>">
            <input type="hidden" name="applicant_name" value="<?php echo htmlspecialchars($row['applicant_full_name']); ?>">
            <input type="hidden" name="child_name" value="<?php echo htmlspecialchars($row['child_full_name']); ?>">
            <input type="hidden" name="marks" value="<?php echo htmlspecialchars($row['marks']); ?>">
            <input type="hidden" name="feedback" value="<?php echo htmlspecialchars($row['feedback']); ?>">
            
            <textarea name="message_db" placeholder="Write a message to applicant..." required></textarea>
            
            <div style="display:flex; gap:10px;">
                <button type="submit" name="send_message_db">📨 Send (DB)</button> 
                <button type="button" class="send-email-btn" data-form-id="<?php echo $row['id']; ?>">✉️ Send Email</button>
            </div>
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

// EmailJS send
document.querySelectorAll('.send-email-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const formId = btn.getAttribute('data-form-id');
        const form = document.getElementById('message-form-' + formId);
        
        // **IMPORTANT:** Get the message content from the DB textarea name for consistency
        const message = form.querySelector('textarea[name="message_db"]').value; 
        
        // Get other values from hidden fields
        const recipientEmail = form.querySelector('input[name="recipient_email"]').value;
        const applicantName = form.querySelector('input[name="applicant_name"]').value;
        const childName = form.querySelector('input[name="child_name"]').value;
        const marks = form.querySelector('input[name="marks"]').value;
        const feedback = form.querySelector('input[name="feedback"]').value;

        if (!message) { alert("⚠️ Message cannot be empty."); return; }
        if (!recipientEmail) { alert("⚠️ Recipient email is missing."); return; } // Safety check

        // Send the email using EmailJS
        // NOTE: Make sure "service_j4maamw" and "template_81bw8t5" are correct
        emailjs.send("service_j4maamw", "template_81bw8t5", {
            to_email: recipientEmail, // The recipient's email is correctly set to the applicant's email
            applicant_name: applicantName,
            child_name: childName,
            marks: marks,
            feedback: feedback,
            message: message // The message content
        })
        .then(() => alert("✅ Email sent successfully!"))
        .catch(err => alert("❌ Failed to send email: " + err.text));
    });
});
</script>

</body>
</html>