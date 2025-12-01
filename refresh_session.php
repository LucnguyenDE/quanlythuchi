<!-- CHƯA SÀI TỚI -->
<?php
// --- Kết nối SQL Server ---
$serverName = "localhost"; 
$connectionOptions = [
    "Database" => "GPLX_CSDT",
    "Uid" => "",        
    "PWD" => "",    
    "CharacterSet" => "UTF-8"  
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(json_encode([
        "status" => "error",
        "message" => "Không kết nối được database"
    ]));
}

// --- Lấy toàn bộ dữ liệu học viên ---
$sql = "
    SELECT n.HoVaTen, n.NgaySinh, n.SoCMT,
           h.HangGPLX, h.KetQua_LyThuyet, h.KetQuaSHM,
           h.KetQua_Hinh, h.KetQua_Duong, h.NgayRaQDTN
    FROM NguoiLX n
    JOIN NguoiLX_HoSo h ON n.MaDK = h.MaDK
    WHERE h.MaBC2 IN ('56/2025TT','57/2025TT','58/2025TT','59/2025TT',
                      '67/2025TT','68/2025TT','69/2025TT','70/2025TT')
    ORDER BY 
        LTRIM(RIGHT(n.HoVaTen, CHARINDEX(' ', REVERSE(n.HoVaTen) + ' ') - 1))
        COLLATE Vietnamese_CI_AS,
        n.HoVaTen COLLATE Vietnamese_CI_AS
";

$stmt = sqlsrv_query($conn, $sql);

// Lấy dữ liệu vào mảng
$data = [];
if ($stmt && sqlsrv_has_rows($stmt)) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // format ngày sinh + ngày ra QĐ TN sang string để JS dễ xử lý
        if ($row['NgaySinh'] instanceof DateTime) {
            $row['NgaySinh'] = $row['NgaySinh']->format('Y-m-d');
        }
        if ($row['NgayRaQDTN'] instanceof DateTime) {
            $row['NgayRaQDTN'] = $row['NgayRaQDTN']->format('Y-m-d');
        }
        $data[] = $row;
    }
}

// --- Cập nhật session ---
$_SESSION['hocvien_data'] = $data;

// --- Trả về JSON ---
echo json_encode([
    "status" => "success",
    "total_records" => count($data),
    "hocvien_data" => $data
]);

sqlsrv_close($conn);
