<?php
// filepath: e:\xampp\htdocs\website\gioi_thieu.php
session_start();
include "header.php";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giới thiệu</title>
    <link rel="stylesheet" href="style.css">
    <link rel ="stylesheet" href="gioi_thieu.css">
</head>
<body>
    <div class="about-container">
        <img src="hinh_anh/logo.png" alt="Logo" />
        <h1>Giới thiệu về chúng tôi</h1>
        <p>
            <b>Trang Web Của Tôi</b> là địa chỉ mua sắm trực tuyến uy tín, chuyên cung cấp các sản phẩm chất lượng với giá cả hợp lý. Chúng tôi luôn đặt lợi ích khách hàng lên hàng đầu và không ngừng cải tiến dịch vụ để mang lại trải nghiệm tốt nhất.
        </p>
        <h2>Sứ mệnh</h2>
        <p>
            Mang đến cho khách hàng những sản phẩm tốt nhất, dịch vụ tận tâm và sự hài lòng tuyệt đối.
        </p>
        <h2>Giá trị cốt lõi</h2>
        <ul>
            <li>Chất lượng sản phẩm là ưu tiên số 1</li>
            <li>Khách hàng là trung tâm</li>
            <li>Đổi mới và sáng tạo</li>
            <li>Trách nhiệm với cộng đồng</li>
        </ul>
        <h2>Thông tin liên hệ</h2>
        <p>
            Địa chỉ: 123 Đường ABC, Quận 1, TP.HCM<br>
            Điện thoại: 0123 456 789<br>
            Email: info@email.com
        </p>
        <h2>Đội ngũ phát triển</h2>
        <ul>
            <li>Nguyễn Văn A - Quản lý dự án</li>
            <li>Trần Thị B - Lập trình viên</li>
            <li>Lê Văn C - Thiết kế giao diện</li>
        </ul>
        <div style="text-align:center;margin-top:24px;">
            <a href="index.php" style="display:inline-block;padding:8px 18px;background:#7c3aed;color:#fff;border-radius:6px;text-decoration:none;transition:background 0.2s;">← Về trang chủ</a>
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>