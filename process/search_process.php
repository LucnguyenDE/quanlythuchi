<?php
// Nhận keyword
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// --- Lần đầu load dữ liệu từ DB nếu chưa có trong session ---
if (!isset($_SESSION['hocvien_data'])) {
    $sql = "
        SELECT n.HoVaTen, n.NgaySinh, n.SoCMT,
               h.HangGPLX, h.KetQua_LyThuyet, h.KetQuaSHM,
               h.KetQua_Hinh, h.KetQua_Duong, h.NgayRaQDTN
        FROM NguoiLX n
        JOIN NguoiLX_HoSo h ON n.MaDK = h.MaDK
        WHERE h.MaBC2 IN ('56/2025TT','57/2025TT','58/2025TT','59/2025TT')
        ORDER BY 
            LTRIM(RIGHT(n.HoVaTen, CHARINDEX(' ', REVERSE(n.HoVaTen) + ' ') - 1))
            COLLATE Vietnamese_CI_AS,
            n.HoVaTen COLLATE Vietnamese_CI_AS
    ";
    $stmt = sqlsrv_query($conn, $sql);
    $data = [];
    if ($stmt && sqlsrv_has_rows($stmt)) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
    }
    $_SESSION['hocvien_data'] = $data; // lưu vào session
} else {
    $data = $_SESSION['hocvien_data']; // lấy từ session
}

// --- Xử lý tìm kiếm nếu có keyword ---
$result = []; // luôn khởi tạo mảng
if ($keyword !== '') {
   $keyword_lower = mb_strtolower($keyword, 'UTF-8');
        foreach ($data as $row) {
            $name_lower = mb_strtolower($row['HoVaTen'], 'UTF-8');
            $cmt = $row['SoCMT'];
            if (strpos($name_lower, $keyword_lower) !== false || strpos($cmt, $keyword_lower) !== false) {
                $result[] = $row;
            }
        }
} else {
    $result = $data;
}

// --- Tính tổng bản ghi & phân trang ---
$total_records = count($result);
$total_pages = ceil($total_records / $limit);
$result_page = array_slice($result, $start, $limit);
?>
