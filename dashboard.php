<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$role = $_SESSION['role'];
$page = $_GET['page'] ?? 'register';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Medical System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>Medical System</h1>
        <div class="user-info">
            <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> (<?= htmlspecialchars($role) ?>)</span>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="layout">
        <div class="sidebar">
            <div class="sidebar-menu">
                <?php if ($role === 'receptionist'): ?>
                    <a href="?page=register" class="menu-item <?= $page === 'register' ? 'active' : '' ?>">Register Patient</a>
                    <a href="?page=patients" class="menu-item <?= $page === 'patients' ? 'active' : '' ?>">View Patients</a>
                <?php elseif ($role === 'doctor'): ?>
                    <a href="?page=consultations" class="menu-item <?= $page === 'consultations' ? 'active' : '' ?>">Consultations</a>
                    <a href="?page=patients" class="menu-item <?= $page === 'patients' ? 'active' : '' ?>">Patient List</a>
                <?php elseif ($role === 'admin'): ?>
                    <a href="?page=dashboard" class="menu-item <?= $page === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                    <a href="?page=users" class="menu-item <?= $page === 'users' ? 'active' : '' ?>">Manage Users</a>
                    <a href="?page=reports" class="menu-item <?= $page === 'reports' ? 'active' : '' ?>">Reports</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="main-content">
            <?php if ($role === 'receptionist'): ?>
                <?php if ($page === 'register'): ?>
                    <?php include 'receptionist_dashboard.php'; ?>
                <?php elseif ($page === 'patients'): ?>
                    <?php include 'view_patients.php'; ?>
                <?php endif; ?>
            <?php elseif ($role === 'doctor'): ?>
                <h2>Doctor Dashboard</h2>
                <p>Doctor features coming soon...</p>
            <?php elseif ($role === 'admin'): ?>
                <h2>Admin Dashboard</h2>
                <p>Admin features coming soon...</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
