<?php
// Fetch statistics
$totalPatients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$todayPatients = $pdo->query("SELECT COUNT(*) FROM patients WHERE DATE(created_at) = CURDATE()")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$confirmedPatients = $pdo->query("SELECT COUNT(*) FROM patients WHERE status = 'confirmed'")->fetchColumn();

// Fetch recent patients
$stmt = $pdo->query("SELECT * FROM patients ORDER BY created_at DESC LIMIT 10");
$recentPatients = $stmt->fetchAll();
?>

<div class="admin-section">
    <h2>Admin Dashboard</h2>
    
    <div class="patients-stats">
        <div class="stat-card">
            <h3><?= $totalPatients ?></h3>
            <p>Total Patients</p>
        </div>
        <div class="stat-card">
            <h3><?= $todayPatients ?></h3>
            <p>Today's Patients</p>
        </div>
        <div class="stat-card">
            <h3><?= $confirmedPatients ?></h3>
            <p>Confirmed Queue</p>
        </div>
        <div class="stat-card">
            <h3><?= $totalUsers ?></h3>
            <p>System Users</p>
        </div>
    </div>
    
    <h3>Recent Patients</h3>
    <table class="patients-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Symptoms</th>
                <th>Status</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recentPatients)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px;">No patients found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($recentPatients as $patient): ?>
                <tr>
                    <td><?= htmlspecialchars($patient['id']) ?></td>
                    <td><?= htmlspecialchars($patient['name']) ?></td>
                    <td><?= htmlspecialchars($patient['age']) ?></td>
                    <td><?= htmlspecialchars($patient['gender']) ?></td>
                    <td><?= htmlspecialchars($patient['contact']) ?></td>
                    <td><?= htmlspecialchars($patient['symptoms']) ?></td>
                    <td>
                        <span class="status-badge status-<?= htmlspecialchars($patient['status']) ?>">
                            <?= htmlspecialchars($patient['status']) ?>
                        </span>
                    </td>
                    <td><?= date('M d, Y h:i A', strtotime($patient['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
