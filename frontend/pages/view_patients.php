<?php
// Handle confirm action
if (isset($_GET['confirm'])) {
    $patientId = intval($_GET['confirm']);
    $stmt = $conn->prepare("UPDATE patients SET status = 'confirmed' WHERE id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $stmt->close();
    
    header('Location: ?page=patients&filter=' . ($_GET['filter'] ?? 'all'));
    exit;
}

// Handle cancel action
if (isset($_GET['cancel'])) {
    $patientId = intval($_GET['cancel']);
    $stmt = $conn->prepare("UPDATE patients SET status = 'cancelled' WHERE id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $stmt->close();
    
    header('Location: ?page=patients&filter=' . ($_GET['filter'] ?? 'all'));
    exit;
}

// Fetch all patients
$filter = $_GET['filter'] ?? 'all';

if ($filter === 'today') {
    $result = $conn->query("SELECT * FROM patients WHERE DATE(created_at) = CURDATE() ORDER BY created_at DESC");
} else {
    $result = $conn->query("SELECT * FROM patients ORDER BY created_at DESC");
}

$patients = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="patients-view">
    <div class="page-header">
        <h2>Patient Records</h2>
        <div class="filter-buttons">
            <a href="?page=patients&filter=all" class="filter-btn <?= $filter === 'all' ? 'active' : '' ?>">All Patients</a>
            <a href="?page=patients&filter=today" class="filter-btn <?= $filter === 'today' ? 'active' : '' ?>">Today's Patients</a>
        </div>
    </div>
    
    <div class="patients-stats">
        <div class="stat-card">
            <h3><?= count($patients) ?></h3>
            <p><?= $filter === 'today' ? "Today's Patients" : 'Total Patients' ?></p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($patients, fn($p) => $p['status'] === 'waiting')) ?></h3>
            <p>Waiting</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($patients, fn($p) => $p['status'] === 'confirmed')) ?></h3>
            <p>Confirmed</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($patients, fn($p) => $p['status'] === 'in-consultation')) ?></h3>
            <p>In Consultation</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($patients, fn($p) => $p['status'] === 'completed')) ?></h3>
            <p>Completed</p>
        </div>
        <div class="stat-card">
            <h3><?= count(array_filter($patients, fn($p) => $p['status'] === 'cancelled')) ?></h3>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($patients)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 30px;">No patients found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($patients as $patient): ?>
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
                    <td>
                        <?php if ($patient['status'] === 'waiting'): ?>
                            <a href="?page=patients&filter=<?= $filter ?>&confirm=<?= $patient['id'] ?>" 
                               class="btn-confirm">Confirm</a>
                            <a href="?page=patients&filter=<?= $filter ?>&cancel=<?= $patient['id'] ?>" 
                               class="btn-cancel" 
                               onclick="return confirm('Cancel this patient visit?')">Cancel</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
