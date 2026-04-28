<?php
// Fetch all patients
$stmt = $pdo->query("SELECT * FROM patients ORDER BY created_at DESC");
$allPatients = $stmt->fetchAll();
?>

<div class="patients-view">
    <h2>All Patient Records</h2>
    
    <div class="patients-stats">
        <div class="stat-card">
            <h3><?= count($allPatients) ?></h3>
            <p>Total Patients</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($allPatients, fn($p) => $p['status'] === 'waiting')) ?></h3>
            <p>Waiting</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($allPatients, fn($p) => $p['status'] === 'confirmed')) ?></h3>
            <p>Confirmed</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($allPatients, fn($p) => $p['status'] === 'in-consultation')) ?></h3>
            <p>In Consultation</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($allPatients, fn($p) => $p['status'] === 'completed')) ?></h3>
            <p>Completed</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($allPatients, fn($p) => $p['status'] === 'cancelled')) ?></h3>
            <p>Cancelled</p>
        </div>
    </div>
    
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
            <?php if (empty($allPatients)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px;">No patients found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($allPatients as $patient): ?>
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
