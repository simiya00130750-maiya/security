<?php
session_start();

// ลบค่า session ทั้งหมด
$_SESSION = array();

// ทำลาย session
session_destroy();

// ป้องกันการย้อนกลับด้วยปุ่ม Back
header("Location: login.php");
exit();
?>