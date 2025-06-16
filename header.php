<?php
// filepath: e:\xampp\htdocs\website\header.php
// Nếu phiên làm việc (session) chưa được khởi động thì khởi động session
if (session_status() === PHP_SESSION_NONE) session_start();
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Web Của Tôi</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="hinh_anh/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="tim_kiem.css">
    <script src="header.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="hinh_anh/logo.png" alt="logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="gioi_thieu.php">Giới thiệu</a></li>
                <li><a href="san_pham.php">Sản phẩm</a></li>
                <li><a href="#">Liên hệ</a></li> 
                <li><a href="gio_hang.php">Giỏ hàng</a></li>
                <?php if (isset($_SESSION['user'])): ?>  // isset Kiểm tra xem biến đã được khai báo và KHÔNG phải là null
                    <li class="dropdown">
                        <a href="#">
                            <i class="fa-solid fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['user']); ?> <i class="fa-solid fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-content">
                            <li><a href="cai_dat.php"><i class="fa-solid fa-gear"></i> Cài đặt</a></li>
                            <?php if ($_SESSION['user'] === 'admin'): ?>
                                <li><a href="quan_ly.php"><i class="fa-solid fa-box"></i> Quản lý sản phẩm</a></li>
                            <?php endif; ?>
                            <li><a href="dangxuat.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="dangnhap.php">Đăng nhập</a></li>
                    <li><a href="dangky.php">Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <form class="search-form" method="get" action="">
            <input type="text" name="search" placeholder="Tìm sản phẩm..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">🔍</button>
        </form>
    </header>
</body>