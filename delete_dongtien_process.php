<?php
include "db_connect.php";
$SoCMT = $_GET['SoCMT'] ?? '';
$id = $_GET['id'] ?? 0;

if ($id == 0) die("Dữ liệu không hợp lệ.");

$sql = "DELETE FROM LichSuDongTienSatHach WHERE ID = ?";
$params = [$id];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt) {
    header("Location: lichsudongtien.php?SoCMT=".$SoCMT);
    exit;
} else {
    die(print_r(sqlsrv_errors(), true));
}
