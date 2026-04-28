<?php
session_start();
require_once '../backend/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$role = $_SESSION['role'];

// Set default page based on role
if (!isset($_GET['page'])) {
    if ($role === 'receptionist') {
        $page = 'register';
    } elseif ($role === 'doctor') {
        $page = 'consultations';
    } elseif ($role === 'admin') {
        $page = 'dashboard';
    }
} else {
    $page = $_GET['page'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Medical System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="header">
        <h1>Medical System</h1>
        <div class="user-info">
            <a href="#" onclick="showLogoutModal(); return false;">Logout</a>
        </div>
    </div>
    
    <div class="layout">
        <div class="sidebar">
            <div class="sidebar-menu">
                <?php if ($role === 'receptionist'): ?>
                    <a href="?page=register" class="menu-item <?= $page === 'register' ? 'active' : '' ?>">Register Patient</a>
                    <a href="?page=patients" class="menu-item <?= $page === 'patients' ? 'active' : '' ?>">View Patients</a>
                    <a href="?page=profile" class="menu-item <?= $page === 'profile' ? 'active' : '' ?>">Profile</a>
                <?php elseif ($role === 'doctor'): ?>
                    <a href="?page=consultations" class="menu-item <?= $page === 'consultations' ? 'active' : '' ?>">Consultations</a>
                    <a href="?page=patients" class="menu-item <?= $page === 'patients' ? 'active' : '' ?>">Patient List</a>
                    <a href="?page=profile" class="menu-item <?= $page === 'profile' ? 'active' : '' ?>">Profile</a>
                <?php elseif ($role === 'admin'): ?>
                    <a href="?page=dashboard" class="menu-item <?= $page === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                    <a href="?page=users" class="menu-item <?= $page === 'users' ? 'active' : '' ?>">Manage Users</a>
                    <a href="?page=reports" class="menu-item <?= $page === 'reports' ? 'active' : '' ?>">Reports</a>
                    <a href="?page=profile" class="menu-item <?= $page === 'profile' ? 'active' : '' ?>">Profile</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="main-content">
            <?php if ($page === 'profile'): ?>
                <?php include 'pages/profile.php'; ?>
            <?php elseif ($role === 'receptionist'): ?>
                <?php if ($page === 'register'): ?>
                    <?php include 'pages/receptionist_dashboard.php'; ?>
                <?php elseif ($page === 'patients'): ?>
                    <?php include 'pages/view_patients.php'; ?>
                <?php endif; ?>
            <?php elseif ($role === 'doctor'): ?>
                <?php if ($page === 'consultations'): ?>
                    <?php include 'pages/doctor_dashboard.php'; ?>
                <?php elseif ($page === 'patients'): ?>
                    <?php include 'pages/doctor_patients.php'; ?>
                <?php endif; ?>
            <?php elseif ($role === 'admin'): ?>
                <?php if ($page === 'dashboard'): ?>
                    <?php include 'pages/admin_dashboard.php'; ?>
                <?php elseif ($page === 'users'): ?>
                    <?php include 'pages/admin_users.php'; ?>
                <?php elseif ($page === 'reports'): ?>
                    <?php include 'pages/admin_reports.php'; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Logout Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h3>Confirm Logout</h3>
            <p>Are you sure you want to logout?</p>
            <div class="modal-buttons">
                <button onclick="hideLogoutModal()" class="btn-cancel-modal">Cancel</button>
                <a href="../backend/logout.php" class="btn-logout-modal">Logout</a>
            </div>
        </div>
    </div>
    
    <script>
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }
        
        function hideLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('logoutModal');
            if (event.target == modal) {
                hideLogoutModal();
            }
        }
    </script>
</body>
</html>
