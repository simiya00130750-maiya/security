<?php
include('db.php');

// --- Auto-Seed Roles if Empty ---
$check_roles = $conn->query("SELECT count(*) as count FROM roles");
$role_count = $check_roles->fetch_assoc();
if ($role_count['count'] == 0) {
    $conn->query("INSERT INTO roles (role_name) VALUES ('Admin'), ('User'), ('Security')");
}

// --- Fetch Roles for Dropdown ---
$roles_result = $conn->query("SELECT * FROM roles");
$roles_options = "";
if ($roles_result->num_rows > 0) {
    while($role_row = $roles_result->fetch_assoc()) {
        $roles_options .= '<option value="'.$role_row['role_id'].'">'.$role_row['role_name'].'</option>';
    }
}

// --- Handle Form Submissions ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'add') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $email = $_POST['email'];
            $role_id = $_POST['role_id'];
            $status = $_POST['status'];

            $sql = "INSERT INTO users (username, password_hash, email, role_id, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssis", $username, $password, $email, $role_id, $status);
            
            try {
                if ($stmt->execute()) {
                    $alert = '<div class="alert alert-success">เพิ่มผู้ใช้เรียบร้อยแล้ว</div>';
                } else {
                    $alert = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' . $conn->error . '</div>';
                }
            } catch (mysqli_sql_exception $e) {
                $alert = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' . $e->getMessage() . '</div>';
            }
            $stmt->close();
        } elseif ($action == 'edit') {
            $user_id = $_POST['user_id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $role_id = $_POST['role_id'];
            $status = $_POST['status'];
            
            try {
                // Should we update password?
                if (!empty($_POST['password'])) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET username=?, password_hash=?, email=?, role_id=?, status=? WHERE user_id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssisi", $username, $password, $email, $role_id, $status, $user_id);
                } else {
                    $sql = "UPDATE users SET username=?, email=?, role_id=?, status=? WHERE user_id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssisi", $username, $email, $role_id, $status, $user_id);
                }

                if ($stmt->execute()) {
                    $alert = '<div class="alert alert-success">แก้ไขข้อมูลเรียบร้อยแล้ว</div>';
                } else {
                    $alert = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' . $conn->error . '</div>';
                }
            } catch (mysqli_sql_exception $e) {
                $alert = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' . $e->getMessage() . '</div>';
            }
            $stmt->close();
        }
    }
}

// --- Handle Delete ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    try {
        if ($stmt->execute()) {
            $alert = '<div class="alert alert-success">ลบข้อมูลเรียบร้อยแล้ว</div>';
        } else {
            $alert = '<div class="alert alert-danger">เกิดข้อผิดพลาดในการลบ: ' . $conn->error . '</div>';
        }
    } catch (mysqli_sql_exception $e) {
        $alert = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' . $e->getMessage() . '</div>';
    }
    $stmt->close();
}

// --- Fetch Users (with Role Name) ---
$sql = "SELECT users.*, roles.role_name FROM users LEFT JOIN roles ON users.role_id = roles.role_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบรักษาความปลอดภัยในหมู่บ้าน - จัดการผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .content-section { padding: 30px 0; margin-top: 20px; }
        .table th { background-color: #f8f9fa; }
    </style>
</head>
<body class="bg-light">

    <?php include('master/navbar.php'); ?>

    <div class="container content-section">
        <h2 class="mb-4"><i class="fas fa-users-cog"></i> จัดการผู้ใช้ระบบ</h2>
        
        <?php if(isset($alert)) echo $alert; ?>

        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus"></i> เพิ่มผู้ใช้ใหม่
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th style="width: 150px;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['user_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo isset($row['role_name']) ? $row['role_name'] : $row['role_id']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $row['last_login']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-btn" 
                                            data-id="<?php echo $row['user_id']; ?>"
                                            data-username="<?php echo htmlspecialchars($row['username']); ?>"
                                            data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                            data-role="<?php echo $row['role_id']; ?>"
                                            data-status="<?php echo $row['status']; ?>"
                                            data-bs-toggle="modal" data-bs-target="#editUserModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?action=delete&id=<?php echo $row['user_id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center">ไม่พบข้อมูลผู้ใช้</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST">
                    <input type="hidden" name="action" value="add">
                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่มผู้ใช้ใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role_id" class="form-select" required>
                                <option value="">เลือกสิทธิ์การใช้งาน...</option>
                                <?php echo $roles_options; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="modal-header">
                        <h5 class="modal-title">แก้ไขผู้ใช้</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" id="edit_username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password (ปล่อยว่างหากไม่ต้องการเปลี่ยน)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role_id" id="edit_role_id" class="form-select" required>
                                <option value="">เลือกสิทธิ์การใช้งาน...</option>
                                <?php echo $roles_options; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-warning">อัปเดต</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Javascript to populate edit modal
        const editModal = document.getElementById('editUserModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const username = button.getAttribute('data-username');
            const email = button.getAttribute('data-email');
            const role = button.getAttribute('data-role');
            const status = button.getAttribute('data-status');

            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role_id').value = role;
            document.getElementById('edit_status').value = status;
        });
    </script>
</body>
</html>