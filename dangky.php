<?php
// filepath: e:\xampp\htdocs\website\dangky.php
session_start();
$conn = new mysqli("localhost", "root", "", "phu_tung_oto");
$thongbao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $email = trim($_POST["email"]);

    // Kiểm tra tài khoản đã tồn tại chưa
    $sql_check = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql_check);
    if ($result->num_rows > 0) {
        $thongbao = "Tên đăng nhập đã tồn tại!";
    } else {
        // Mã hóa mật khẩu
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql_insert = "INSERT INTO users (username, password, email) VALUES ('$username', '$password_hash', '$email')";
        if ($conn->query($sql_insert) === TRUE) {
            $thongbao = "Đăng ký thành công! <a href='dangnhap.php'>Đăng nhập ngay</a>";
        } else {
            $thongbao = "Lỗi: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <form class="form-dangky" method="post" action="">
        <h2>Đăng ký tài khoản</h2>
        <?php if ($thongbao != ""): ?>
            <div class="tb"><?php echo $thongbao; ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng ký</button>
        <div style="text-align:center;margin:16px 0 10px 0;">
            <a href="login_google.php" class="btn-social google">Đăng ký bằng Google</a>
            <a href="login-facebook.php" class="btn-social facebook">Đăng ký bằng Facebook</a>
        </div>
        <div style="text-align:center;margin-top:10px;">
            Đã có tài khoản? <a href="dangnhap.php">Đăng nhập</a>
        </div>
    </form>
</body>
</html>