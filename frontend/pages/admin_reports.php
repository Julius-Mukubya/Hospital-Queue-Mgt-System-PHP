<?php
// Fetch statistics by status
$result = $conn->query("SELECT status, COUNT(*) as count FROM patients GROUP BY status");
$statusStats = $result->fetch_all(MYSQLI_ASSOC);

// Total patients
$totalPatients = $conn->query("SELECT COUNT(*) FROM patients")->fetch_row()[0];
$todayPatients = $conn->query("SELECT COUNT(*) FROM patients WHERE DATE(created_at) = CURDATE()")->fetch_row()[0];
$totalCancelled = $conn->query("SELECT COUNT(*) FROM patients WHERE status = 'cancelled'")->fetch_row()[0];
$totalCompleted = $conn->query("SELECT COUNT(*) FROM patients WHERE status = 'completed'")->fetch_row()[0];

// Completion rate
$completionRate = $totalPatients > 0 ? round(($totalCompleted / $totalPatients) * 100, 1) : 0;
$cancellationRate = $totalPatients > 0 ? round(($totalCancelled / $totalPatients) * 100, 1) : 0;

// Gender breakdown
$maleCount = $conn->query("SELECT COUNT(*) FROM patients WHERE gender = 'Male'")->fetch_row()[0];
$femaleCount = $conn->query("SELECT COUNT(*) FROM patients WHERE gender = 'Female'")->fetch_row()[0];

// Peak hour (most patients registered)
$result = $conn->query("SELECT HOUR(created_at) as hour, COUNT(*) as count FROM patients GROUP BY HOUR(created_at) ORDER BY count DESC LIMIT 1");
$peakHour = $result->fetch_assoc();

// Daily stats for last 7 days
$result = $conn->query("
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as total,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
        SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) as male,
        SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as female
    FROM patients 
    GROUP BY DATE(created_at) 
    ORDER BY date DESC 
    LIMIT 7
");
$dailyStats = $result->fetch_all(MYSQLI_ASSOC);

// Average age
$avgAge = $conn->query("SELECT ROUND(AVG(age), 1) FROM patients")->fetch_row()[0] ?? 0;
?>

<div class="admin-reports">
    <h2>Reports & Analytics</h2>

    <div class="report-section">
        <h3>Summary Overview</h3>
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
                <h3><?= $completionRate ?>%</h3>
                <p>Completion Rate</p>
            </div>
            <div class="stat-card">
                <h3><?= $cancellationRate ?>%</h3>
                <p>Cancellation Rate</p>
            </div>
        </div>
    </div>

    <div class="report-section">
        <h3>Patient Status Breakdown</h3>
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
        <h3>Gender Breakdown</h3>
        <div class="patients-stats">
            <div class="stat-card">
                <h3><?= $maleCount ?></h3>
                <p>Male</p>
            </div>
            <div class="stat-card">
                <h3><?= $femaleCount ?></h3>
                <p>Female</p>
            </div>
        </div>
    </div>

    <div class="report-section">
        <h3>Last 7 Days Activity</h3>
        <table class="patients-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Completed</th>
                    <th>Cancelled</th>
                    <th>Male</th>
                    <th>Female</th>
                    <th>Completion Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dailyStats)): ?>
                    <tr><td colspan="7" style="text-align:center; padding:30px;">No data available</td></tr>
                <?php else: ?>
                    <?php foreach ($dailyStats as $stat): ?>
                    <tr>
                        <td><?= date('M d, Y', strtotime($stat['date'])) ?></td>
                        <td><?= $stat['total'] ?></td>
                        <td><?= $stat['completed'] ?></td>
                        <td><?= $stat['cancelled'] ?></td>
                        <td><?= $stat['male'] ?></td>
                        <td><?= $stat['female'] ?></td>
                        <td><?= $stat['total'] > 0 ? round(($stat['completed'] / $stat['total']) * 100, 1) : 0 ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
