<?php
include "db_connect.php";

// Nhận SoCMT từ URL
$SoCMT = $_GET['SoCMT'] ?? '';

if ($SoCMT === '') {
    die("Không có thông tin học viên.");
}

// Lấy tất cả lịch sử đóng tiền của người này
$sql = "SELECT * FROM LichSuDongTienSatHach WHERE SoCMT = ? ORDER BY NgayDongTien";
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
            <th>STT</th>    
            <th>Ngày sát hạch</th>    
            <th>Ngày đóng tiền</th>
            <th>Môn đóng tiền</th>
            <th>Số tiền</th>
            <th>Người đóng tiền</th>
            <th>Người nhận tiền</th>
            <th>Ghi chú</th>
            <th>Chức năng</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(sqlsrv_has_rows($stmt)){
            $count = 1;
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                echo "<tr>";
                echo "<td>".$count++."</td>";
                echo "<td>29/11/2025</td>";
                echo "<td>".($row['NgayDongTien'] ? $row['NgayDongTien']->format('d-m-Y') : '')."</td>";
                echo "<td>".htmlspecialchars($row['MonDongTien'])."</td>";
                echo "<td>".number_format((float)$row['SoTienDong'], 0, ',', '.')."</td>";
                echo "<td>".htmlspecialchars($row['NguoiDongTien'])."</td>";
                echo "<td>".htmlspecialchars($row['NguoiNhanTien'])."</td>";
                echo "<td>".htmlspecialchars($row['Ghichu'])."</td>";
                //sửa-xóa
                echo "<td>
                <button class='btn btn-warning btn-sm editBtn' 
                    data-id='".$row['ID']."'
                    data-mon='".$row['MonDongTien']."'
                    data-tien='".$row['SoTienDong']."'
                    data-ngdong='".$row['NguoiDongTien']."'
                    data-ngnhan='".$row['NguoiNhanTien']."'
                    data-ghichu='".$row['Ghichu']."'
                >Sửa</button>
                <a href='delete_dongtien_process.php?id=".$row['ID']."&SoCMT=".$row['SoCMT']."' 
                class='btn btn-danger btn-sm'
                onclick=\"return confirm('Bạn có chắc muốn xóa?');\">Xóa</a>
                    </td>";
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
<!-- Modal sửa -->
 <!-- Modal sửa -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm" method="POST" action="update_dongtien_process.php">
        <input type="hidden" name="SoCMT" value="<?php echo $_GET['SoCMT']; ?>">
        <div class="modal-header">
          <h5 class="modal-title">Sửa thông tin đóng tiền</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="ID" id="editID">
            <div class="mb-2">
    <label>Môn đóng tiền</label>
    <div id="editMonDongTien"> 
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="MonDongTien[]" id="mon1" value="Lý thuyết" >
            <label class="form-check-label" for="mon1">Lý thuyết</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="MonDongTien[]" id="mon2" value="Mô Phỏng">
            <label class="form-check-label" for="mon2">Mô Phỏng</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="MonDongTien[]" id="mon3" value="Sa Hình">
            <label class="form-check-label" for="mon3">Sa Hình</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="MonDongTien[]" id="mon4" value="Đường trường">
            <label class="form-check-label" for="mon4">Đường trường</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="MonDongTien[]" id="mon5" value="Lệ phí thi">
            <label class="form-check-label" for="mon5">Lệ phí thi</label>
        </div>
    </div>
            <div class="mb-2">
                <label>Số tiền</label>
                <input type="text" class="form-control" name="SoTienDong" id="editSoTienDong">
            </div>
            <div class="mb-2">
                <label>Người đóng tiền</label>
                <input type="text" class="form-control" name="NguoiDongTien" id="editNguoiDongTien">
            </div>
            <div class="mb-2">
                <label>Người nhận tiền</label>
                <input type="text" class="form-control" name="NguoiNhanTien" id="editNguoiNhanTien">
            </div>
            <div class="mb-2">
                <label>Ghi chú</label>
                <textarea class="form-control" name="Ghichu" id="editGhichu"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
      </form>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/modal.js"></script>
</body>
</html>

<?php
sqlsrv_close($conn);
?>
