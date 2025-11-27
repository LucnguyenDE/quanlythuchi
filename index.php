<?php
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

// Lấy tổng số bản ghi
$total_sql = "SELECT COUNT(*) as total
              FROM NguoiLX n
              JOIN NguoiLX_HoSo h ON n.MaDK = h.MaDK
              WHERE h.MaBC2 IN ('56/2025TT', '57/2025TT', '58/2025TT', '59/2025TT','67/2025TT', '68/2025TT', '69/2025TT', '70/2025TT')";
$total_stmt = sqlsrv_query($conn, $total_sql);
$total_row = sqlsrv_fetch_array($total_stmt, SQLSRV_FETCH_ASSOC);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Lấy dữ liệu trang hiện tại
$sql = "SELECT 
            n.HoVaTen, n.NgaySinh, n.SoCMT,
            h.HangGPLX, h.KetQua_LyThuyet, h.KetQuaSHM, h.KetQua_Hinh, h.KetQua_Duong, h.NgayRaQDTN
        FROM NguoiLX n
        JOIN NguoiLX_HoSo h ON n.MaDK = h.MaDK
        WHERE h.MaBC2 IN ('67/2025TT', '68/2025TT', '69/2025TT', '70/2025TT')
        ORDER BY n.HoVaTen
        OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$params = [$start, $limit];
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách học viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Danh sách học viên</h2>

    <p class="text-center">
        Hiển thị từ <?php echo $start + 1; ?> đến 
        <?php 
            $end = ($start + $limit) > $total_records ? $total_records : ($start + $limit);
            echo $end;
        ?> trên tổng số <?php echo $total_records; ?> học viên
    </p>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Sinh ngày</th>
                <th>CCCD</th>
                <th>Hạng</th>
                <th>L</th>
                <th>M</th>
                <th>H</th>
                <th>Đ</th>
                <th>QĐ Tốt Nghiệp</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stt = $start + 1;
            if(sqlsrv_has_rows($stmt)){
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                    //Hạng GPLX
                    // $hang = trim($row['HangGPLX']);
                    // if ($hang === 'B11' || $hang === 'B1') {
                    //     $hang_display = 'B STĐ';
                    // }
                    // if ($hang === 'B2') {
                    //     $hang_display = 'B sàn';
                    // } else {
                    //     $hang_display = $hang;
                    // }
                    $hang = $row['HangGPLX'];
                    if ($hang !== null) {
                        $hang = (string)$hang;             // ép kiểu sang chuỗi
                        $hang = preg_replace('/[\x00-\x1F\x7F]/u', '', $hang); // loại bỏ ký tự vô hình
                        $hang = trim($hang);               // loại bỏ khoảng trắng đầu/cuối
                    }
                   if ($hang == 'B11' || $hang == 'B1') {
                    $hang_display = 'B TĐ';
                    } elseif ($hang == 'B2') {
                    $hang_display = 'B sàn';
                    } else {
                    $hang_display = $hang;
                    }

                    echo "<tr>";
                    echo "<td>".$stt."</td>"; $stt++;
                    echo "<td>".htmlspecialchars($row['HoVaTen'])."</td>";

                    // Ngày sinh
                    echo "<td>";
                    if (!empty($row['NgaySinh'])) {
                    $date = new DateTime($row['NgaySinh']);  // tạo đối tượng DateTime từ chuỗi SQL
                    echo $date->format('d-m-Y');            // định dạng thành ngày-tháng-năm
                    }
                    echo "</td>";
                    echo "<td>".htmlspecialchars($row['SoCMT'])."</td>";
                    echo "<td>".htmlspecialchars($hang_display)."</td>";
                    echo "<td>".htmlspecialchars($row['KetQua_LyThuyet'])."</td>";
                    echo "<td>".htmlspecialchars($row['KetQuaSHM'])."</td>";
                    echo "<td>".htmlspecialchars($row['KetQua_Hinh'])."</td>";
                    echo "<td>".htmlspecialchars($row['KetQua_Duong'])."</td>";

                    // Ngày ra QĐ tốt nghiệp
                    echo "<td>".($row['NgayRaQDTN'] ? $row['NgayRaQDTN']->format('d-m-Y') : '')."</td>";


                    echo "<td>
                        <a href='dongtien_thilai.php?SoCMT=".urlencode($row['SoCMT'])."' class='btn btn-success btn-sm mb-1'>Đóng tiền</a>
                        <a href='sua_ketqua.php?SoCMT=".urlencode($row['SoCMT'])."' class='btn btn-primary btn-sm'>Cập nhật KQSH</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- PHÂN TRANG -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page-1; ?>">&laquo; Trước</a>
                </li>
            <?php endif; ?>

            <?php
            for($i=1; $i<=$total_pages; $i++){
                $active = ($i == $page) ? "active" : "";
                echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
            }
            ?>

            <?php if($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page+1; ?>">Sau &raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
sqlsrv_close($conn);
?>
