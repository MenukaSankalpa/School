<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userInput = trim($_POST['email']); // can be username or email
    $inputPassword = $_POST['password'];

    // Fetch the user by username OR email
    $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $userInput, $userInput);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $dbPassword = $user['password'];

        $isValid = false;

        // ✅ Check password type
        if (strlen($dbPassword) === 32 && ctype_xdigit($dbPassword)) {
            // Old MD5 password
            if (md5($inputPassword) === $dbPassword) {
                $isValid = true;
            }
        } elseif (str_starts_with($dbPassword, '$2y$')) {
            // New bcrypt hashed password
            if (password_verify($inputPassword, $dbPassword)) {
                $isValid = true;
            }
        }

        if ($isValid) {
            // Set session
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect by role
            switch ($user['role']) {
                case '1':
                    header("Location: parent/parent_dash.php");
                    break;
                case '2':
                    header("Location: school_admin/school_admin_dashboard.php");
                    break;
                case '3':
                    header("Location: admin/layout.php");
                    break;
                default:
                    echo "<script>alert('Unknown user role!'); window.location.href='index.html'</script>";
                    exit;
            }
            exit;
        }
    }

    echo "<script>alert('Invalid Login Credentials!'); window.location.href='index.html'</script>";
    exit;
}

$conn->close();
?>
