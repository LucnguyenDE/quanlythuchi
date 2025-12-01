<?php
include "../db/db_connect.php";
$SoCMT = $_POST['SoCMT']??'';
$id = $_POST['ID'];
$MonDongTien = implode('+ ', $_POST['MonDongTien']);
$SoTienDong = str_replace('.', '', $_POST['SoTienDong']); // Quan trọng !!!
$NguoiDongTien = $_POST['NguoiDongTien'];
$NguoiNhanTien = $_POST['NguoiNhanTien'];
$Ghichu = $_POST['Ghichu'];


$sql = "UPDATE LichSuDongTienSatHach 
        SET MonDongTien=?, SoTienDong=?, NguoiDongTien=?, NguoiNhanTien=?, Ghichu=?
        WHERE ID=?";

$params = [$MonDongTien, $SoTienDong, $NguoiDongTien, $NguoiNhanTien, $Ghichu, $id];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt) {
    header("Location: ../pages/lichsudongtien.php?SoCMT=".$SoCMT);
    exit;
} else {
    die(print_r(sqlsrv_errors(), true));
}
