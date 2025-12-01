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
?>
