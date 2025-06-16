<?php
// filepath: e:\xampp\htdocs\website\dangnhap.php
session_start();
require_once "connect.php";
$thongbao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $thongbao = "Sai mật khẩu!";
        }
    } else {
        $thongbao = "Tài khoản không tồn tại!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <form class="form-dangnhap" method="post" action="">
        <h2>Đăng nhập</h2>
        <?php if ($thongbao != ""): ?>
            <div class="tb"><?php echo $thongbao; ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
        <div style="text-align:center;margin:16px 0 10px 0;">
            <a href="login_google.php" class="btn-social google">Đăng nhập bằng Google</a>
            <a href="login-facebook.php" class="btn-social facebook">Đăng nhập bằng Facebook</a>
        </div>
        <div style="text-align:center;margin-top:10px;">
            Chưa có tài khoản? <a href="dangky.php">Đăng ký</a>
        </div>
        <div style="text-align:center;margin-top:10px;">
            <a href="quenmatkhau.php" style="color:#7c3aed;text-decoration:underline;">Quên mật khẩu?</a>
        </div>
    </form>
</body>
</html>