<?php
include "../db/db_connect.php";
$_SESSION['error']='';
$_SESSION['username']='';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
// 1. Lấy user theo username
$sql = "SELECT * FROM Users WHERE Username = ?";
$params = [$username];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($user && isset($user['PasswordHash'])) {
    // 2. Kiểm tra password với hash
    if (password_verify($password, $user['PasswordHash'])) {
        // Đăng nhập thành công
        $_SESSION['username'] = $username;
        header("Location: ../index.php");
        exit;
    }
}

// Nếu không đúng
$_SESSION['error'] = "Sai tài khoản hoặc mật khẩu!";
header("Location: ../pages/login.php");
exit;
?>