<?php
include('db.php');

// --- Statistics Queries ---

// 1. Total Events
$res_total = $conn->query("SELECT count(*) as count FROM security_events");
$total_events = $res_total->fetch_assoc()['count'];

// 2. Events by Severity
$res_high = $conn->query("SELECT count(*) as count FROM security_events WHERE severity='high'");
$high_events = $res_high->fetch_assoc()['count'];

$res_med = $conn->query("SELECT count(*) as count FROM security_events WHERE severity='medium'");
$medium_events = $res_med->fetch_assoc()['count'];

$res_low = $conn->query("SELECT count(*) as count FROM security_events WHERE severity='low'");
$low_events = $res_low->fetch_assoc()['count'];

// 3. Device Status
$res_online = $conn->query("SELECT count(*) as count FROM devices WHERE status='online'");
$online_devices = $res_online->fetch_assoc()['count'];

$res_offline = $conn->query("SELECT count(*) as count FROM devices WHERE status='offline'");
$offline_devices = $res_offline->fetch_assoc()['count'];

// 4. Total Users
$res_users = $conn->query("SELECT count(*) as count FROM users");
$total_users = $res_users->fetch_assoc()['count'];

// 5. Recent 5 Events
$recent_events = $conn->query("SELECT e.*, d.device_name FROM security_events e LEFT JOIN devices d ON e.device_id = d.device_id ORDER BY e.event_time DESC LIMIT 5");

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบรักษาความปลอดภัย - รายงานสรุปผล</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .content-section { padding: 30px 0; margin-top: 20px; }
        .card-icon { font-size: 2.5rem; opacity: 0.3; position: absolute; right: 20px; top: 20px; }
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">

    <?php include('master/navbar.php'); ?>

    <div class="container content-section">
        <h2 class="mb-4"><i class="fas fa-chart-line"></i> รายงานสรุปผล (Dashboard)</h2>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white h-100 stat-card shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-calendar-alt card-icon"></i>
                        <h5 class="card-title">เหตุการณ์ทั้งหมด</h5>
                        <h2 class="display-4 fw-bold"><?php echo $total_events; ?></h2>
                        <p class="card-text">ครั้ง</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white h-100 stat-card shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-exclamation-circle card-icon"></i>
                        <h5 class="card-title">ความเสี่ยงสูง</h5>
                        <h2 class="display-4 fw-bold"><?php echo $high_events; ?></h2>
                        <p class="card-text">ครั้ง</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white h-100 stat-card shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-video card-icon"></i>
                        <h5 class="card-title">อุปกรณ์ออนไลน์</h5>
                        <h2 class="display-4 fw-bold"><?php echo $online_devices; ?></h2>
                        <p class="card-text">เครื่อง</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-dark h-100 stat-card shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-users card-icon"></i>
                        <h5 class="card-title">ผู้ใช้งานในระบบ</h5>
                        <h2 class="display-4 fw-bold"><?php echo $total_users; ?></h2>
                        <p class="card-text">คน</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Chart Section -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">สถิติความรุนแรงของเหตุการณ์</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="severityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Events List -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">เหตุการณ์ล่าสุด (5 รายการ)</h5>
                        <a href="security.php" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if ($recent_events->num_rows > 0): ?>
                                <?php while($row = $recent_events->fetch_assoc()): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo $row['event_type']; ?></h6>
                                            <small class="text-muted"><?php echo date('d/m/H:i', strtotime($row['event_time'])); ?></small>
                                        </div>
                                        <p class="mb-1 small text-muted">อุปกรณ์: <?php echo $row['device_name']; ?></p>
                                        <small>
                                            <span class="badge bg-<?php 
                                                echo $row['severity'] == 'high' ? 'danger' : ($row['severity'] == 'medium' ? 'warning text-dark' : 'success'); 
                                            ?>"><?php echo ucfirst($row['severity']); ?></span>
                                        </small>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="list-group-item text-center">ไม่มีข้อมูลเหตุการณ์</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Chart.js Configuration
        const ctx = document.getElementById('severityChart').getContext('2d');
        const severityChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['สูง (High)', 'ปานกลาง (Medium)', 'ต่ำ (Low)'],
                datasets: [{
                    label: '# of Events',
                    data: [<?php echo $high_events; ?>, <?php echo $medium_events; ?>, <?php echo $low_events; ?>],
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.8)', // Red
                        'rgba(255, 193, 7, 0.8)', // Yellow
                        'rgba(25, 135, 84, 0.8)'  // Green
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
</body>
</html>
