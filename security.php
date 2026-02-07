<?php
include('db.php');

// --- Auto-Seed Locations ---
// Check if table locations exists or just try to select count
try {
    $check_loc = $conn->query("SELECT count(*) as count FROM locations");
    if ($check_loc) {
        $loc_count = $check_loc->fetch_assoc();
        if ($loc_count['count'] == 0) {
            echo "<!-- Seeding Locations -->";
            $conn->query("INSERT INTO locations (location_id, location_name) VALUES (1, 'Main Gate'), (2, 'Backyard'), (3, 'Kitchen')");
        }
    }
} catch (Exception $e) {
    // Look like location table might not exist or other error, ignore for now or handle gracefully
}

// --- Auto-Seed Device Types ---
$check_types = $conn->query("SELECT count(*) as count FROM device_types");
$type_count = $check_types->fetch_assoc();
if ($type_count['count'] == 0) {
    echo "<!-- Seeding Device Types -->";
    $conn->query("INSERT INTO device_types (type_id, type_name) VALUES (1, 'CCTV Camera'), (2, 'Motion Sensor'), (3, 'Smoke Detector')");
}

// --- Auto-Seed Devices ---
$check_devices = $conn->query("SELECT count(*) as count FROM devices");
$device_count = $check_devices->fetch_assoc();
if ($device_count['count'] == 0) {
    echo "<!-- Seeding Devices -->";
    // Assuming locations are just IDs for now
    $conn->query("INSERT INTO devices (device_id, device_name, type_id, location_id, status, install_date) VALUES 
        (1, 'Main Gate Camera', 1, 1, 'online', '2023-01-15'),
        (2, 'Backyard Sensor', 2, 2, 'online', '2023-02-10'),
        (3, 'Kitchen Smoke Detector', 3, 3, 'online', '2023-03-05')");
}

// --- Handle Add Event ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_event') {
    $device_id = $_POST['device_id'];
    $event_type = $_POST['event_type'];
    $severity = $_POST['severity'];
    $description = $_POST['description'];

    $sql = "INSERT INTO security_events (device_id, event_type, severity, description, event_time) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $device_id, $event_type, $severity, $description);
    
    if ($stmt->execute()) {
        $alert = '<div class="alert alert-success">บันทึกเหตุการณ์เรียบร้อยแล้ว</div>';
    } else {
        $alert = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' . $conn->error . '</div>';
    }
    $stmt->close();
}

// --- Fetch Events ---
$sql = "SELECT e.*, d.device_name, dt.type_name 
        FROM security_events e 
        LEFT JOIN devices d ON e.device_id = d.device_id 
        LEFT JOIN device_types dt ON d.type_id = dt.type_id 
        ORDER BY e.event_time DESC";
$result = $conn->query($sql);

// --- Fetch Devices for Dropdown ---
$devices_result = $conn->query("SELECT * FROM devices");
$device_options = "";
while($d = $devices_result->fetch_assoc()) {
    $device_options .= '<option value="'.$d['device_id'].'">'.$d['device_name'].'</option>';
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบรักษาความปลอดภัย - บันทึกเหตุการณ์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .content-section { padding: 30px 0; margin-top: 20px; }
        .severity-low { background-color: #d4edda; color: #155724; }
        .severity-medium { background-color: #fff3cd; color: #856404; }
        .severity-high { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body class="bg-light">

    <?php include('master/navbar.php'); ?>

    <div class="container content-section">
        <h2 class="mb-4"><i class="fas fa-shield-alt"></i> บันทึกเหตุการณ์ความปลอดภัย</h2>

        <?php if(isset($alert)) echo $alert; ?>

        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addEventModal">
                <i class="fas fa-exclamation-triangle"></i> แจ้งเหตุการณ์ใหม่
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>เวลาที่เกิดเหตุ</th>
                                <th>อุปกรณ์</th>
                                <th>ประเภทอุปกรณ์</th>
                                <th>ประเภทเหตุการณ์</th>
                                <th>ระดับความรุนแรง</th>
                                <th>รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <?php 
                                        $severity_class = '';
                                        if($row['severity'] == 'low') $severity_class = 'severity-low';
                                        elseif($row['severity'] == 'medium') $severity_class = 'severity-medium';
                                        elseif($row['severity'] == 'high') $severity_class = 'severity-high';
                                        
                                        $severity_label = ucfirst($row['severity']);
                                        if($severity_label == 'Low') $severity_label = 'ต่ำ';
                                        if($severity_label == 'Medium') $severity_label = 'ปานกลาง';
                                        if($severity_label == 'High') $severity_label = 'สูง';
                                    ?>
                                <tr>
                                    <td><?php echo $row['event_time']; ?></td>
                                    <td><?php echo $row['device_name']; ?></td>
                                    <td><?php echo $row['type_name']; ?></td>
                                    <td><?php echo $row['event_type']; ?></td>
                                    <td><span class="badge <?php echo $severity_class; ?> fs-6"><?php echo $severity_label; ?></span></td>
                                    <td><?php echo $row['description']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center">ยังไม่มีรายการเหตุการณ์</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST">
                    <input type="hidden" name="action" value="add_event">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">แจ้งเหตุการณ์ความปลอดภัย</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>อุปกรณ์ที่ตรวจพบ</label>
                            <select name="device_id" class="form-select" required>
                                <option value="">เลือกอุปกรณ์...</option>
                                <?php echo $device_options; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>ประเภทเหตุการณ์</label>
                            <input type="text" name="event_type" class="form-control" placeholder="เช่น ตรวจพบการเคลื่อนไหว, ประตูเปิดค้าง" required>
                        </div>
                        <div class="mb-3">
                            <label>ระดับความรุนแรง</label>
                            <select name="severity" class="form-select" required>
                                <option value="low">ต่ำ (Low)</option>
                                <option value="medium">ปานกลาง (Medium)</option>
                                <option value="high">สูง (High)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>รายละเอียดเพิ่มเติม</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-danger">บันทึกแจ้งเหตุ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>