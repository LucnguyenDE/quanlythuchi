<?php
include "db_connect.php";

// Nhận dữ liệu từ form
$HoVaTen       = $_POST['HoVaTen'] ?? '';
$NgaySinh      = $_POST['NgaySinh'] ?? '';
$SoCMT         = $_POST['SoCMT'] ?? '';
$MonDongTienArray = $_POST['MonDongTien'] ?? [];
if (!empty($MonDongTienArray)) {
    // Chuyển thành chuỗi phân tách bằng dấu phẩy
    $MonDongTien = implode(', ', $MonDongTienArray);
} else {
    $MonDongTien = '';
}
$NguoiDongTien = $_POST['NguoiDongTien'] ?? '';
$NguoiNhanTien = $_POST['NguoiNhanTien'] ?? '';
$SoTienDong    = $_POST['SoTienDong'] ?? 0;
$Ghichu        = $_POST['Ghichu'] ?? '';
$NgaySinh_sql = '';
if (!empty($NgaySinh)) {
    $dt = DateTime::createFromFormat('d-m-Y', $NgaySinh); // form d-m-Y từ form
    if ($dt !== false) {
        $NgaySinh_sql = $dt->format('Ymd'); // 8 ký tự, chuẩn DB
    }
}

// Insert vào bảng LichSuDongTienSatHach
$sql = "INSERT INTO LichSuDongTienSatHach
        (HoVaTen, NgaySinh, SoCMT, MonDongTien, NguoiDongTien, NguoiNhanTien, SoTienDong, Ghichu)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$params = [$HoVaTen, $NgaySinh_sql, $SoCMT, $MonDongTien, $NguoiDongTien, $NguoiNhanTien, $SoTienDong, $Ghichu];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    // refresh session nếu muốn
    header("Location: index.php");
    exit;
}
