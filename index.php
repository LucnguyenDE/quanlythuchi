<?php
include "db_connect.php";
include "search_process.php";
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
    <!-- <div class="d-flex justify-content-end mb-3">
        <span class="me-2">Xin chào, <?php echo htmlspecialchars($username); ?></span>
        <a href="logout.php" class="btn btn-sm btn-outline-secondary">Đăng xuất</a>
    </div> -->
    <h2>Danh sách học viên</h2>

    <p class="text-center">
        Hiển thị từ <?php echo ($total_records > 0) ? ($start + 1) : 0; ?> đến 
        <?php 
            $end = ($start + $limit) > $total_records ? $total_records : ($start + $limit);
            echo $end;
        ?> trên tổng số <?php echo $total_records; ?> học viên
    </p>

    <form method="GET" style="margin-bottom: 15px;">
        <input type="text" name="keyword" placeholder="Nhập tên hoặc CCCD..." style="padding: 6px; width: 250px;" value="<?php echo htmlspecialchars($keyword); ?>">
        <button type="submit" style="padding: 6px 12px;">Tìm học viên</button>
    </form>

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
            if (!empty($result_page)) {
                $stt = $start + 1;
                foreach ($result_page as $row) {
                    $hang = $row['HangGPLX'];
                    if ($hang !== null) {
                        $hang = trim(preg_replace('/[\x00-\x1F\x7F]/u', '', (string)$hang));
                    }
                    if ($hang == 'B11' || $hang == 'B1') $hang_display = 'B TĐ';
                    elseif ($hang == 'B2') $hang_display = 'B sàn';
                    else $hang_display = $hang;

                    echo "<tr>";
                    echo "<td>".$stt."</td>"; $stt++;
                    echo "<td>
                    <a href='lichsudongtien.php?SoCMT=".urlencode($row['SoCMT'])."'>
                            ".htmlspecialchars($row['HoVaTen'])."
                    </a>
                    </td>";
                    echo "<td>".(!empty($row['NgaySinh']) ? (new DateTime($row['NgaySinh']))->format('d-m-Y') : '')."</td>";
                    echo "<td>".htmlspecialchars($row['SoCMT'])."</td>";
                    echo "<td>".htmlspecialchars($hang_display)."</td>";
                    echo "<td>".htmlspecialchars($row['KetQua_LyThuyet'])."</td>";
                    echo "<td>".htmlspecialchars($row['KetQuaSHM'])."</td>";
                    echo "<td>".htmlspecialchars($row['KetQua_Hinh'])."</td>";
                    echo "<td>".htmlspecialchars($row['KetQua_Duong'])."</td>";
                    echo "<td>".($row['NgayRaQDTN'] ? $row['NgayRaQDTN']->format('d-m-Y') : '')."</td>";
                    echo "<td><a href='dongtien.php?SoCMT=".urlencode($row['SoCMT'])."' class='btn btn-success btn-sm mb-1'>Đóng tiền</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11' class='text-center'>Không tìm thấy học viên</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- PHÂN TRANG -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page-1; ?>&keyword=<?php echo urlencode($keyword); ?>">&laquo; Trước</a></li>
            <?php endif; ?>

            <?php
            for($i=1; $i<=$total_pages; $i++){
                $active = ($i == $page) ? "active" : "";
                echo "<li class='page-item $active'><a class='page-link' href='?page=$i&keyword=".urlencode($keyword)."'>$i</a></li>";
            }
            ?>

            <?php if($page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page+1; ?>&keyword=<?php echo urlencode($keyword); ?>">Sau &raquo;</a></li>
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
