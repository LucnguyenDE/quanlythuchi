<?php
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;   
// xuatexcel.php
include "../db/db_connect.php";
// --- Truy vấn dữ liệu ---
$sql = "SELECT HoVaTen, SoCMT, NgayDongTien, MonDongTien, SoTienDong
        FROM LichSuDongTienSatHach 
        ORDER BY NgayDongTien";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die("Lỗi truy vấn: " . print_r(sqlsrv_errors(), true));
}

// --- Tạo file Excel ---
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header
$sheet->setCellValue('A1', 'STT')
      ->setCellValue('B1', 'Họ và tên')
      ->setCellValue('C1', 'CCCD')
      ->setCellValue('D1', 'Ngày đóng tiền')
      ->setCellValue('E1', 'Môn đóng tiền')
      ->setCellValue('F1', 'Số tiền đã đóng');


// Format header
$sheet->getStyle('A1:F1')->getFont()->setBold(true);
$sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');

// Ghi dữ liệu
$rowNum = 2;
$stt = 1;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $ngayDongTien = $row['NgayDongTien'] ? $row['NgayDongTien']->format('Y-m-d') : '';
    
    $sheet->setCellValue('A'.$rowNum, $stt)
          ->setCellValue('B'.$rowNum, $row['HoVaTen'])
          ->setCellValue('C'.$rowNum, $row['SoCMT'])
          ->setCellValue('D'.$rowNum, $ngayDongTien)
          ->setCellValue('E'.$rowNum, $row['MonDongTien'])
          ->setCellValue('F'.$rowNum, $row['SoTienDong']);

    
    $sheet->getStyle('A'.$rowNum.':F'.$rowNum)
          ->getAlignment()->setHorizontal('center');
    
    $rowNum++;
    $stt++;
}

// Tự động điều chỉnh độ rộng cột
foreach(range('A','F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Xuất file
$filename = "Danhsachdongtien.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit();
?>