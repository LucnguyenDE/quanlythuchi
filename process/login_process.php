<?php
session_start(); // Bắt buộc để dùng $_SESSION
include "db_connect.php"; // kết nối $conn SQL Server

$_SESSION['error'] = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username !== '' && $password !== '') {
        // Truy vấn user từ database
        $sql = "SELECT Username, PasswordHash FROM Users WHERE Username = ?";
        $params = [$username];
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true)); // debug nếu query lỗi
        }

        if (sqlsrv_has_rows($stmt)) {
            $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

            if ($user && hash('sha256', $password) === $user['PasswordHash']) {
                // Đăng nhập thành công
                $_SESSION['username'] = $user['Username'];
                header("Location: index.php");
                exit; // <--- Bắt buộc phải có để ngăn PHP tiếp tục
            } else {
                $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
                echo "conmemay1";
                header("Location: login.php");
                exit;
            }
        } else {
                echo "conmemay2";
            $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Vui lòng nhập tên đăng nhập và mật khẩu!";
        header("Location: login.php");
        exit;
    }
}

sqlsrv_close($conn);
?>
