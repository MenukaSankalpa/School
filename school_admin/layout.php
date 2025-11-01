<?php
// Get current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
    </div>
    <nav class="sidebar-nav">
        <a href="school_admin_dashboard.php" class="<?= ($current_page == 'school_admin_dashboard.php') ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="applicant_list.php" class="<?= ($current_page == 'applicant_list.php') ? 'active' : '' ?>">
            <i class="fas fa-user-graduate"></i> Applicants
        </a>
        <a href="status.php" class="<?= ($current_page == 'status.php') ? 'active' : '' ?>">
            <i class="fas fa-check-circle"></i> Status
        </a>
        <a href="logout.php" class="<?= ($current_page == 'logout.php') ? 'active' : '' ?>">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
/* Modern Sidebar with Icons */
.sidebar {
    width: 240px;
    background-color: #1e3a8a;
    color: #fff;
    height: 100vh;
    display: flex;
    flex-direction: column;
    position: fixed;
}

.sidebar-header {
    text-align: center;
    padding: 30px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.sidebar-header h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    margin-top: 20px;
}

.sidebar-nav a {
    text-decoration: none;
    color: #fff;
    padding: 15px 25px;
    font-weight: 500;
    transition: background 0.3s, color 0.3s;
    border-left: 4px solid transparent;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 16px;
}

.sidebar-nav a i {
    font-size: 18px;
}

.sidebar-nav a:hover {
    background-color: #2563eb;
    border-left: 4px solid #fff;
}

.sidebar-nav a.active {
    background-color: #3b82f6;
    border-left: 4px solid #fff;
}
</style>
