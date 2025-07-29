<?php
session_start();
include '../db.php'; 


if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaint = trim($_POST['complaint'] ?? '');

    if (empty($complaint)) {
        $error = "Please enter your complaint or question.";
    } else {
       
        $sql = "INSERT INTO complaints (user_id, complaint_text, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $complaint);

        if ($stmt->execute()) {
            $success = "Your complaint has been submitted successfully.";
            
            $_POST['complaint'] = '';
        } else {
            $error = "Failed to submit complaint. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Submit Complaint</title>
<style>
    
    * {
        box-sizing: border-box;
    }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f9fafb;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        color: #333;
    }
    .container {
        background: white;
        margin: 40px 20px;
        padding: 30px 40px;
        max-width: 600px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    h1 {
        margin-bottom: 20px;
        font-weight: 700;
        font-size: 1.8rem;
        color: #2c3e50;
        text-align: center;
    }
    label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 1rem;
        color: #34495e;
    }
    textarea {
        width: 100%;
        min-height: 140px;
        padding: 14px 18px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: border-color 0.3s ease;
    }
    textarea:focus {
        border-color: #3498db;
        outline: none;
    }
    button {
        background-color: #3498db;
        border: none;
        color: white;
        padding: 14px 28px;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 20px;
        width: 100%;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #2980b9;
    }
    .error, .success {
        margin-bottom: 20px;
        padding: 12px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-align: center;
    }
    .error {
        background-color: #ffe6e6;
        color: #d93025;
        border: 1px solid #d93025;
    }
    .success {
        background-color: #e6f4ea;
        color: #188038;
        border: 1px solid #188038;
    }
    a {
        display: inline-block;
        margin-top: 25px;
        color: #3498db;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    a:hover {
        color: #2980b9;
        text-decoration: underline;
    }
    @media (max-width: 480px) {
        .container {
            padding: 20px 20px;
            margin: 20px 10px;
        }
        h1 {
            font-size: 1.5rem;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h1>Submit a Complaint or Question</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" action="submit_complaint.php" novalidate>
        <label for="complaint">Your Complaint or Question:</label>
        <textarea name="complaint" id="complaint" rows="6" required><?= htmlspecialchars($_POST['complaint'] ?? '') ?></textarea>
        <button type="submit">Submit</button>
    </form>

    <a href="../parent/parent_dash.php">Back to Dashboard</a>
</div>

</body>
</html>
