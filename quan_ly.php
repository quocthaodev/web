<?php
// filepath: e:\xampp\htdocs\website\quan_ly.php
session_start();
require_once "connect.php";
include "header.php";
// Kiểm tra đăng nhập admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

// Xử lý xóa sản phẩm (không cho xóa nếu có đơn hàng liên quan)
$thongbao = "";
if (isset($_GET['xoa']) && is_numeric($_GET['xoa'])) {
    $id = intval($_GET['xoa']);
    // Kiểm tra có đơn hàng liên quan không
    $check = $conn->query("SELECT 1 FROM don_hang WHERE san_pham_id = $id LIMIT 1");
    if ($check && $check->num_rows > 0) {
        // Chuyển hướng và truyền thông báo lỗi
        header("Location: quan_ly.php?tb=notdelete");
        exit();
    }
    $conn->query("DELETE FROM san_pham WHERE san_pham_id = $id");
    header("Location: quan_ly.php");
    exit();
}

// Xử lý thêm sản phẩm với upload ảnh
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['them'])) {
    $ten_sp = trim($_POST['ten_sp']);
    $gia_sp = intval($_POST['gia_sp']);
    $hinh_anh = "";

    // Xử lý upload ảnh
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $target_dir = "hinh_anh/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . basename($_FILES["hinh_anh"]["name"]);
        move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_file);
        $hinh_anh = $target_file;
    }

    if ($ten_sp && $gia_sp && $hinh_anh) {
        $stmt = $conn->prepare("INSERT INTO san_pham (ten_sp, gia_sp, hinh_anh) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $ten_sp, $gia_sp, $hinh_anh);
        $stmt->execute();
        $stmt->close();
        header("Location: quan_ly.php");
        exit();
    }
}

// Lấy danh sách sản phẩm
$result = $conn->query("SELECT * FROM san_pham ORDER BY san_pham_id DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="quan_ly.css">
</head>
<body>
    <div class="admin-container">
        <h2>Quản lý sản phẩm</h2>
        <?php if (isset($_GET['tb']) && $_GET['tb'] == 'notdelete'): ?>
            <div style="color:#fff;background:#ef4444;padding:10px 18px;border-radius:6px;margin-bottom:16px;text-align:center;">
                Không thể xóa! Sản phẩm đã có đơn hàng liên quan.
            </div>
        <?php endif; ?>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="text" name="ten_sp" placeholder="Tên sản phẩm" required>
            <input type="number" name="gia_sp" placeholder="Giá sản phẩm" required>
            <label class="custom-file-upload">
                <input type="file" name="hinh_anh" accept="image/*" required>
                <span id="file-chosen">Chọn ảnh...</span>
            </label>
            <button type="submit" name="them">Thêm sản phẩm</button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Xóa</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['san_pham_id']; ?></td>
                    <td><img src="<?php echo htmlspecialchars($row['hinh_anh']); ?>" alt="" width="60"></td>
                    <td><?php echo htmlspecialchars($row['ten_sp']); ?></td>
                    <td><?php echo number_format($row['gia_sp']); ?> VNĐ</td>
                    <td>
                        <a href="quan_ly.php?xoa=<?php echo $row['san_pham_id']; ?>" class="btn-xoa" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Chưa có sản phẩm nào.</td></tr>
            <?php endif; ?>
        </table>
        <a href="index.php" style="display:inline-block;margin-bottom:18px;padding:8px 18px;background:#7c3aed;color:#fff;border-radius:6px;text-decoration:none;transition:background 0.2s;">← Quay lại</a>
    </div>
    <script>
    document.querySelector('input[type="file"]').addEventListener('change', function(e){
        var fileName = this.files[0] ? this.files[0].name : "Chọn ảnh...";
        document.getElementById('file-chosen').textContent = fileName;
    });
    </script>
    <?php include "footer.php"; ?> 
</body>
</html>