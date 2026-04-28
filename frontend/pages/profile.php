<?php
// Fetch user information
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$userId = $_SESSION['user_id'];
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if (password_verify($currentPassword, $user['password'])) {
        if ($newPassword === $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashedPassword, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();
            $success = "Password changed successfully!";
            
            // Refresh user data
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
        } else {
            $error = "New passwords do not match!";
        }
    } else {
        $error = "Current password is incorrect!";
    }
}
?>

<div class="profile-section">
    <h2>My Profile</h2>
    
    <?php if (isset($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <div class="profile-card">
        <h3>Profile Information</h3>
        <div class="profile-info">
            <div class="info-row">
                <label>Username:</label>
                <span><?= htmlspecialchars($user['username']) ?></span>
            </div>
            <div class="info-row">
                <label>Role:</label>
                <span class="role-badge role-<?= htmlspecialchars($user['role']) ?>">
                    <?= htmlspecialchars(ucfirst($user['role'])) ?>
                </span>
            </div>
            <div class="info-row">
                <label>Account Created:</label>
                <span><?= date('M d, Y h:i A', strtotime($user['created_at'])) ?></span>
            </div>
        </div>
    </div>
    
    <div class="profile-card">
        <h3>Change Password</h3>
        <form method="POST" class="patient-form">
            <div class="form-group">
                <label>Current Password:</label>
                <input type="password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label>New Password:</label>
                <input type="password" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label>Confirm New Password:</label>
                <input type="password" name="confirm_password" required>
            </div>
            
            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>
</div>
