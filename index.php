
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบรักษาความปลอดภัยในหมู่บ้าน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS เพิ่มเติมเพื่อให้ส่วนต่างๆ มีระยะห่าง */
        .content-section {
            padding: 50px 0;
            margin-top: 20px;
        }
        .data-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .data-table-container {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <?php
        // ดึงแถบนำทางเข้ามา
        include('master/navbar.php');
    ?>

    <div id="home" class="container content-section">
        <h2 class="mb-4">🏠 หน้าหลัก (ภาพรวมสถานะปัจจุบัน)</h2>
        <div class="row">

            <div class="col-sm-12 col-md-6 col-lg-3 text-center">
                <div class="data-card bg-primary text-white">
                    <h1>287</h1>
                    <h4>ผู้เข้าออกวันนี้</h4>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-3 text-center">
                <div class="data-card bg-info text-dark">
                    <h1>15,420</h1>
                    <h4>สแกนทะเบียนรถสะสม</h4>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-3 text-center">
                <div class="data-card bg-warning text-dark">
                    <h1>75%</h1>
                    <h4>ปริมาณรถเข้าออก (เทียบกับค่าเฉลี่ย)</h4>
                </div>
            </div>
            
            <div class="col-sm-12 col-md-12 col-lg-3 text-center">
                <div class="data-card bg-danger text-white">
                    <h1>3</h1>
                    <h4>ระบบแจ้งเตือนเหตุฉุกเฉิน (24 ชม.)</h4>
                </div>
            </div>

        </div>
    </div>
    
    <hr>

    
   