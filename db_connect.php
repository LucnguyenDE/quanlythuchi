<?php
session_start();
// --- Cấu hình kết nối SQL Server ---
$serverName = "localhost";
$connectionOptions = [
    "Database" => "GPLX_CSDT",
    "Uid" => "",
    "PWD" => "",
    "CharacterSet" => "UTF-8"
];

// Kết nối
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// --- PHÂN TRANG ---
$limit = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// // Lấy tổng số bản ghi
// $total_sql = "SELECT COUNT(*) as total 
//               FROM NguoiLX n
//               JOIN NguoiLX_HoSo h ON n.MaDK = h.MaDK
//               WHERE h.MaBC2 IN ('56/2025TT','57/2025TT','58/2025TT','59/2025TT','67/2025TT','68/2025TT','69/2025TT','70/2025TT')";
// $total_stmt = sqlsrv_query($conn, $total_sql);
// $total_row = sqlsrv_fetch_array($total_stmt, SQLSRV_FETCH_ASSOC);
// $total_records = $total_row['total'];
// $total_pages = ceil($total_records / $limit);
// ?>
