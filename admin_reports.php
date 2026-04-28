<?php
// Fetch statistics by date
$stmt = $pdo->query("
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as total,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
    FROM patients 
    GROUP BY DATE(created_at) 
    ORDER BY date DESC 
    LIMIT 7
");
$dailyStats = $stmt->fetchAll();

// Fetch statistics by status
$stmt = $pdo->query("
    SELECT status, COUNT(*) as count 
    FROM patients 
    GROUP BY status
");
$statusStats = $stmt->fetchAll();
?>

<div class="admin-reports">
    <h2>Reports & Analytics</h2>
    
    <div class="report-section">
        <h3>Patient Status Overview</h3>
        <div class="patients-stats">
            <?php foreach ($statusStats as $stat): ?>
            <div class="stat-card">
                <h3><?= $stat['count'] ?></h3>
                <p><?= ucfirst(str_replace('-', ' ', $stat['status'])) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="report-section">
        <h3>Last 7 Days Activity</h3>
        <table class="patients-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Total Patients</th>
                    <th>Completed</th>
                    <th>Completion Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dailyStats as $stat): ?>
                <tr>
                    <td><?= date('M d, Y', strtotime($stat['date'])) ?></td>
                    <td><?= $stat['total'] ?></td>
                    <td><?= $stat['completed'] ?></td>
                    <td><?= $stat['total'] > 0 ? round(($stat['completed'] / $stat['total']) * 100, 1) : 0 ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
