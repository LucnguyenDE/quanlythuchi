<?php
include "db_connect.php";

// Nhận SoCMT từ URL
$SoCMT = isset($_GET['SoCMT']) ? $_GET['SoCMT'] : '';

$hocvien = null;

// Tìm học viên trong session
if (isset($_SESSION['hocvien_data']) && $SoCMT !== '') {
    foreach ($_SESSION['hocvien_data'] as $row) {
        if ($row['SoCMT'] === $SoCMT) {
            $hocvien = $row;
            break;
        }
    }
}

// Nếu không tìm thấy, hiển thị thông báo
if (!$hocvien) {
    echo "<p>Không tìm thấy học viên.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đóng tiền sát hạch</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background-color: #f9f9f9;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container {
    max-width: 600px;
    background-color: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-top: 50px;
}

h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
    font-weight: 600;
}

form .mb-3 label {
    font-weight: 500;
    color: #555;
}

form .form-control {
    border-radius: 8px;
    padding: 10px 12px;
    border: 1px solid #ccc;
    transition: border-color 0.3s, box-shadow 0.3s;
}

form .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.2);
}

form textarea.form-control {
    resize: none;
    height: 80px;
}

button.btn {
    border-radius: 8px;
    padding: 10px 20px;
}

button.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

button.btn-primary:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

button.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

button.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}
</style>
</head>
<body>
<div class="container mt-4">
    <h2>Đóng tiền sát hạch</h2>
    
    <form method="POST" action="dongtien_process.php">
        <!-- Trường khóa: SoCMT -->
        <input type="hidden" name="SoCMT" value="<?php echo htmlspecialchars($hocvien['SoCMT']); ?>">

        <div class="mb-3">
            <label>Họ và tên</label>
            <input type="text" class="form-control" name="HoVaTen" 
                   value="<?php echo htmlspecialchars($hocvien['HoVaTen']); ?>" readonly>
        </div>

        <div class="mb-3">
            <label>Ngày sinh</label>
            <input type="text" class="form-control" name="NgaySinh" 
                   value="<?php echo (!empty($hocvien['NgaySinh']) ? (new DateTime($hocvien['NgaySinh']))->format('d-m-Y') : ''); ?>" readonly>
        </div>

        <div class="mb-3">
    <label>Môn đóng tiền</label>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="MonDongTien[]" id="mon_lythuyet" value="Lý thuyết">
        <label class="form-check-label" for="mon_lythuyet">Lý thuyết</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="MonDongTien[]" id="mon_mohinh" value="Mô phỏng">
        <label class="form-check-label" for="mon_mohinh">Mô phỏng</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="MonDongTien[]" id="mon_sahinh" value="Sa hình">
        <label class="form-check-label" for="mon_sahinh">Sa hình</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="MonDongTien[]" id="mon_duongtruong" value="Đường trường">
        <label class="form-check-label" for="mon_duongtruong">Đường trường</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="MonDongTien[]" id="mon_lephi" value="Lệ phí thi">
        <label class="form-check-label" for="mon_lephi">Lệ phí thi</label>
    </div>
</div>

        <div class="mb-3">
            <label>Người đóng tiền</label>
            <input type="text" class="form-control" name="NguoiDongTien">
        </div>

        <div class="mb-3">
            <label>Người nhận tiền</label>
            <input type="text" class="form-control" name="NguoiNhanTien">
        </div>

        <div class="mb-3">
            <label>Số tiền đóng</label>
            <input type="text" class="form-control" id="SoTienDong" name="SoTienDong" required>
        </div>

        <div class="mb-3">
            <label>Ghi chú</label>
            <textarea class="form-control" name="Ghichu"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<script src="js/format_sotien.js"></script>
</body>
</html>
