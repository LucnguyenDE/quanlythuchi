<?php
include "db_connect.php";

// Nhận SoCMT từ URL
$SoCMT = $_GET['SoCMT'] ?? '';

if ($SoCMT === '') {
    die("Không có thông tin học viên.");
}

// Lấy tất cả lịch sử đóng tiền của người này
$sql = "SELECT * FROM LichSuDongTienSatHach WHERE SoCMT = ? ORDER BY NgayDongTien DESC";
$params = [$SoCMT];
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Lịch sử đóng tiền</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .container {
        max-width: 900px;
        margin-top: 30px;
        background-color: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
        margin-bottom: 20px;
        color: #343a40;
    }
    table {
        font-size: 0.95rem;
    }
    th {
        background-color: #343a40 !important;
        color: #fff !important;
        text-align: center;
    }
    td {
        vertical-align: middle;
        text-align: center;
    }
    .btn-back {
        margin-top: 15px;
    }
    .student-info {
        margin-bottom: 20px;
        font-weight: 500;
        color: #495057;
    }
    .student-info span {
        font-weight: 700;
        color: #212529;
    }
</style>
</head>
<body>
<div class="container mt-4">
<h2>Lịch sử đóng tiền</h2>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Ngày sát hạch</th>    
            <th>Ngày đóng tiền</th>
            <th>Môn đóng tiền</th>
            <th>Số tiền</th>
            <th>Người đóng tiền</th>
            <th>Người nhận tiền</th>
            <th>Ghi chú</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(sqlsrv_has_rows($stmt)){
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                echo "<tr>";
                echo "<td>29/11/2025</td>";
                echo "<td>".($row['NgayDongTien'] ? $row['NgayDongTien']->format('d-m-Y') : '')."</td>";
                echo "<td>".htmlspecialchars($row['MonDongTien'])."</td>";
                echo "<td>".number_format($row['SoTienDong'], 0, ',', '.')."</td>";
                echo "<td>".htmlspecialchars($row['NguoiDongTien'])."</td>";
                echo "<td>".htmlspecialchars($row['NguoiNhanTien'])."</td>";
                echo "<td>".htmlspecialchars($row['Ghichu'])."</td>";
                echo "</tr>";
            }
        } else {
            echo "<td>29/11/2025</td>";
            echo "<tr><td colspan='6' class='text-center'>Chưa có lịch sử đóng tiền</td></tr>";
        }
        ?>
    </tbody>
</table>
<div style="display: flex; justify-content: space-between;">
<a href="index.php" class="btn btn-secondary">Quay lại</a>
<?php echo "<a href='dongtien.php?SoCMT=".$SoCMT."' class='btn btn-success'>Đóng tiền</a>";?>
</div>
</div>
</body>
</html>

<?php
sqlsrv_close($conn);
?>
