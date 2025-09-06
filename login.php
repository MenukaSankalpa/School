<?php
include 'db.php';
session_start();

$userInput = $_POST['email'];
$password = $_POST['password']; // plain text

// --- Check in users table (existing system) ---
$sql = "SELECT * FROM users WHERE (email = ? OR username = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $userInput, $userInput);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // Verify password stored as MD5
    if (md5($password) === $user['password']) {
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == '1') {
            header("Location: parent/parent_dash.php");
        } elseif ($user['role'] == '2') {
            header("Location: admin_dashboard.php");
        } elseif ($user['role'] == '3') {
            header("Location: admin/layout.php");
        }
        exit;
    }
}

// --- Check in admins table ---
$sql_admin = "SELECT * FROM admins WHERE email = ? OR username = ?";
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->bind_param("ss", $userInput, $userInput);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();

if ($result_admin && $result_admin->num_rows === 1) {
    $admin = $result_admin->fetch_assoc();

    // If passwords in admins table are hashed using password_hash()
    if (password_verify($password, $admin['password'])) {
        $_SESSION['email'] = $admin['email'];
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['role'] = 'admin'; // optional, just to identify school admin

        // Redirect to school admin dashboard
        header("Location: school_admin/admin_dashboard.php");
        exit;
    }
}

// If login fails for both tables
echo "<script>alert('Invalid Login Credentials!'); window.location.href='index.html'</script>";
$conn->close();
?>
