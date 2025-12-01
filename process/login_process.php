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
    // Không lộ lỗi DB cho người dùng
    $_SESSION['error'] = "Lỗi hệ thống!";
    header("Location: ../pages/login.php");
    exit;
}
$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// -----------------------------
// CHỐNG USER ENUMERATION + TIMING ATTACK
// -----------------------------
$fake_hash = '$2y$10$KFaQzGv5d2L1IO2o0pOddOIoU7wQ0Vq1jZP0vyP3xXjK0L2gYV7Na'; 
// Hash giả cố định (bcrypt 60 ký tự). Không quan trọng password.

$hashToCheck = $user['PasswordHash'] ?? $fake_hash;

// So sánh password → nếu user không tồn tại thì vẫn verify với hash giả
$loginOK = password_verify($password, $hashToCheck);

// -----------------------------
// KẾT LUẬN LOGIN
// -----------------------------
if ($loginOK && $user) {
    // Đăng nhập thành công
    $_SESSION['username'] = $user['Username'];
    header("Location: ../index.php");
    exit;
}

// Sai password hoặc user không tồn tại → message giống nhau
$_SESSION['error'] = "Sai tài khoản hoặc mật khẩu!";
header("Location: ../pages/login.php");
exit;
// $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// if ($user && isset($user['PasswordHash'])) {
//     // 2. Kiểm tra password với hash
//         // Đăng nhập thành công
//         $_SESSION['username'] = $username;
//         header("Location: ../index.php");
//    if (password_verify($password, $user['PasswordHash'])) {
//          exit;
//     }
// }

// // Nếu không đúng
// $_SESSION['error'] = "Sai tài khoản hoặc mật khẩu!";
// header("Location: ../pages/login.php");
// exit;
?>