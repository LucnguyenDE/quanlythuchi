<?php

    // --- Nhận keyword ---
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $searchCondition = "";
    $params = [];

// Nếu có từ khóa thì tạo điều kiện tìm kiếm
    if (isset($_GET['keyword']) && trim($_GET['keyword']) !== "") {
   
    // Không phân biệt hoa/thường, có dấu/không dấu
    $searchCondition = "
        AND (
            n.HoVaTen COLLATE Latin1_General_CI_AS LIKE ? 
            OR n.SoCMT LIKE ?
        )
    ";

    $searchParam = "%" . $keyword . "%";
    $params = [$searchParam, $searchParam];

    // Tìm top 500 kết quả duy nhất
    $sql = "
        SELECT TOP 500
            n.HoVaTen, n.NgaySinh, n.SoCMT,
            h.HangGPLX, h.KetQua_LyThuyet, h.KetQuaSHM,
            h.KetQua_Hinh, h.KetQua_Duong, h.NgayRaQDTN
        FROM NguoiLX n
        JOIN NguoiLX_HoSo h ON n.MaDK = h.MaDK
        WHERE h.MaBC2 IN ('56/2025TT','57/2025TT','58/2025TT','59/2025TT','67/2025TT','68/2025TT','69/2025TT','70/2025TT')
        $searchCondition
        ORDER BY 
            LTRIM(RIGHT(n.HoVaTen, CHARINDEX(' ', REVERSE(n.HoVaTen) + ' ') - 1))
            COLLATE Vietnamese_CI_AS,
            n.HoVaTen COLLATE Vietnamese_CI_AS
    ";
    // $stmt = sqlsrv_query($conn, $sql, $params);
} else {
        $sql = "
        SELECT 
            n.HoVaTen, n.NgaySinh, n.SoCMT,
            h.HangGPLX, h.KetQua_LyThuyet, h.KetQuaSHM,
            h.KetQua_Hinh, h.KetQua_Duong, h.NgayRaQDTN
        FROM NguoiLX n
        JOIN NguoiLX_HoSo h ON n.MaDK = h.MaDK
        WHERE h.MaBC2 IN ('56/2025TT','57/2025TT','58/2025TT','59/2025TT','67/2025TT','68/2025TT','69/2025TT','70/2025TT')
        ORDER BY 
            LTRIM(RIGHT(n.HoVaTen, CHARINDEX(' ', REVERSE(n.HoVaTen) + ' ') - 1))
            COLLATE Vietnamese_CI_AS,
            n.HoVaTen COLLATE Vietnamese_CI_AS
        OFFSET ? ROWS FETCH NEXT ? ROWS ONLY
    ";
    $params = [$start, $limit];
}
?>
