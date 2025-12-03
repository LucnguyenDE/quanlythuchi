<?php
include "../db/db_connect.php";

$sql = "SELECT HoVaTen, SoCMT, NgayDongTien, MonDongTien, SoTienDong
        FROM LichSuDongTienSatHach
        ORDER BY NgayDongTien";

$stmt = sqlsrv_query($conn, $sql, [], ["Scrollable" => SQLSRV_CURSOR_KEYSET]);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$total = sqlsrv_num_rows($stmt);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách học viên đã đóng tiền</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Danh sách học viên đã đóng tiền</h2>
    <a href="../index.php" class="btn btn-secondary">
        Quay lại
    </a>
    <p class="text-center">
        Tổng số: <strong><?php echo $total; ?></strong> lượt đóng tiền
    </p>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>CCCD</th>
                <th>Ngày đóng tiền</th>
                <th>Môn đóng tiền</th>
                <th>Số tiền</th>
            </tr>
        </thead>
        <tbody>

        <?php
        $stt = 1;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

            $ngay = "";
            if (!empty($row["NgayDongTien"])) {
                $ngay = $row["NgayDongTien"]->format("d-m-Y");
            }

            echo "<tr>";
            echo "<td>".$stt++."</td>";
            echo "<td>".htmlspecialchars($row['HoVaTen'])."</td>";
            echo "<td>".htmlspecialchars($row['SoCMT'])."</td>";
            echo "<td>".$ngay."</td>";
            echo "<td>".htmlspecialchars($row['MonDongTien'])."</td>";
            echo "<td>" . number_format($row['SoTienDong'], 0, ',', '.') . " VNĐ</td>";
            echo "</tr>";
        }
        ?>

        </tbody>
    </table>
    <a href="../process/xuat_excel_process.php" class="btn btn-success">
        Xuất Excel
    </a>
</div>

</body>
</html>

<?php sqlsrv_close($conn); ?>