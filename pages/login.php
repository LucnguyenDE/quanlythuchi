<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng nhập</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background-color:#f0f2f5; font-family:sans-serif;}
.login-container {max-width:400px; margin:80px auto; padding:30px; background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
h2{text-align:center; margin-bottom:25px;}
</style>
</head>
<body>
<div class="login-container">
    <h2>Đăng nhập</h2>
   <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php endif; ?>
    <form action="../process/login_process.php"  method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
    </form>
</div>
</body>
</html>
