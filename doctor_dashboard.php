<?php
// Fetch confirmed patients (ready for consultation)
$stmt = $pdo->query("SELECT * FROM patients WHERE status = 'confirmed' ORDER BY created_at ASC");
$confirmedPatients = $stmt->fetchAll();

// Fetch today's consultations
$stmt = $pdo->query("SELECT * FROM patients WHERE DATE(created_at) = CURDATE() ORDER BY created_at DESC");
$todayPatients = $stmt->fetchAll();

// Update patient status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $patientId = $_POST['patient_id'];
    $newStatus = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE patients SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $patientId]);
    
    header('Location: ?page=consultations');
    exit;
}
?>

<div class="doctor-section">
    <h2>Consultations</h2>
    
    <div class="patients-stats">
        <div class="stat-card">
            <h3><?= count($confirmedPatients) ?></h3>
            <p>Confirmed Patients</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($todayPatients, fn($p) => $p['status'] === 'in-consultation')) ?></h3>
            <p>In Consultation</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($todayPatients, fn($p) => $p['status'] === 'completed')) ?></h3>
            <p>Completed Today</p>
        </div>
        <div class="stat-card">
            <h3><?= count($todayPatients) ?></h3>
            <p>Total Today</p>
        </div>
    </div>
    
    <h3>Confirmed Patients Queue</h3>
    <table class="patients-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Symptoms</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($confirmedPatients)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px;">No confirmed patients</td>
                </tr>
            <?php else: ?>
                <?php foreach ($confirmedPatients as $patient): ?>
                <tr>
                    <td><?= htmlspecialchars($patient['id']) ?></td>
                    <td><?= htmlspecialchars($patient['name']) ?></td>
                    <td><?= htmlspecialchars($patient['age']) ?></td>
                    <td><?= htmlspecialchars($patient['gender']) ?></td>
                    <td><?= htmlspecialchars($patient['contact']) ?></td>
                    <td><?= htmlspecialchars($patient['symptoms']) ?></td>
                    <td><?= date('h:i A', strtotime($patient['created_at'])) ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">
                            <input type="hidden" name="status" value="in-consultation">
                            <button type="submit" name="update_status" class="btn-start">Start</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <h3>Today's Consultations</h3>
    <table class="patients-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Symptoms</th>
                <th>Status</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($todayPatients)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px;">No consultations today</td>
                </tr>
            <?php else: ?>
                <?php foreach ($todayPatients as $patient): ?>
                <tr>
                    <td><?= htmlspecialchars($patient['id']) ?></td>
                    <td><?= htmlspecialchars($patient['name']) ?></td>
                    <td><?= htmlspecialchars($patient['age']) ?></td>
                    <td><?= htmlspecialchars($patient['symptoms']) ?></td>
                    <td>
                        <span class="status-badge status-<?= htmlspecialchars($patient['status']) ?>">
                            <?= htmlspecialchars($patient['status']) ?>
                        </span>
                    </td>
                    <td><?= date('h:i A', strtotime($patient['created_at'])) ?></td>
                    <td>
                        <?php if ($patient['status'] === 'in-consultation'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" name="update_status" class="btn-complete">Complete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
