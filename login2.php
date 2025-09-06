<?php

include 'db.php';

$userInput = $_POST['email'];
$password = md5($_POST['password']); 

session_start();

// Check in users table
$sql = "SELECT * FROM users WHERE (email = ? OR username = ?) AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $userInput, $userInput, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
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

// check admins table
/*$sql_admin = "SELECT * FROM admins WHERE (email = ? OR username = ?) AND password = ?";
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->bind_param("sss", $userInput, $userInput, $password);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();

if ($result_admin->num_rows === 1) {
    $admin = $result_admin->fetch_assoc();
    $_SESSION['email'] = $admin['email'];
    $_SESSION['user_id'] = $admin['id'];
    $_SESSION['role'] = '3';

    header("Location: school_admin/admin_dashboard.php");
    exit;
}*/

// --- Check in admins table ---
$sql_admin = "SELECT * FROM admins WHERE email = ? OR username = ?";
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->bind_param("ss", $userInput, $userInput);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();

if ($result_admin && $result_admin->num_rows === 1) {
    $admin = $result_admin->fetch_assoc();

    // If your passwords are stored as plain text (not recommended)
    if ($password === $admin['password']) {
        session_start();
        $_SESSION['email'] = $admin['email'];
        $_SESSION['user_id'] = $admin['id'];

        // Redirect to school admin dashboard
        header("Location: school_admin/admin_dashboard.php");
        exit;
    }

    // If your passwords are hashed using password_hash()
    // if (password_verify($_POST['password'], $admin['password'])) {
    //     session_start();
    //     $_SESSION['email'] = $admin['email'];
    //     $_SESSION['user_id'] = $admin['id'];
    //     header("Location: school_admin/admin_dashboard.php");
    //     exit;
    // }
}



echo ("<script>alert('Invalid Login Credentials!'); window.location.href='index.html'</script>");
$conn->close();
?>
