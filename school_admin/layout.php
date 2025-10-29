<!-- layout.php -->
<!-- Add Font Awesome CDN in your main page head if not already included -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
    </div>
    <nav class="sidebar-nav">
        <a href="school_admin_dashboard.php" class="active">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="applicant_list.php">
            <i class="fas fa-user-graduate"></i> Applicants
        </a>
        <a href="status.php">
            <i class="fas fa-check-circle"></i> Status
        </a>
        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</div>

<style>
/* Modern Sidebar with Icons */
.sidebar {
    width: 240px;
    background-color: #1e3a8a; /* deep blue */
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
    background-color: #2563eb; /* hover blue */
    border-left: 4px solid #fff;
}

.sidebar-nav a.active {
    background-color: #3b82f6;
    border-left: 4px solid #fff;
}
</style>
