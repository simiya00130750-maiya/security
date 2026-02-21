<?php
session_start();

// ====== ตั้งค่าฐานข้อมูล ======
$host = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "security";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare("SELECT user_id, username, password_hash, role_id, status FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {

        // ตรวจสอบสถานะ
        if ($user["status"] !== "active") {
            $error = "บัญชีถูกระงับการใช้งาน";
        }
        // ตรวจสอบรหัสผ่าน
        elseif (password_verify($password, $user["password_hash"])) {

            // สร้าง Session
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role_id"] = $user["role_id"];

            // อัปเดต last_login
            $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
            $update->bind_param("i", $user["user_id"]);
            $update->execute();

            header("Location: ./");
            exit();

        } else {
            $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }

    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>เข้าสู่ระบบ</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    height: 100vh;
}
.card {
    border-radius: 15px;
}
</style>
</head>

<body class="d-flex justify-content-center align-items-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg p-4">

                <div class="text-center mb-3">
                    <h3 class="fw-bold text-primary">🔐 เข้าสู่ระบบ</h3>
                </div>

                <?php if($error): ?>
                    <div class="alert alert-danger text-center">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">รหัสผ่าน</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            เข้าสู่ระบบ
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>