<?php
// filepath: e:\xampp\htdocs\website\san_pham.php
session_start();
require_once "connect.php";
include "header.php";

// Xử lý tìm kiếm sản phẩm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT * FROM san_pham";
if ($search != "") {
    $search_sql = $conn->real_escape_string($search);
    $sql .= " WHERE ten_sp LIKE '%$search_sql%'";
}
$sql .= " ORDER BY san_pham_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="san_pham.css">
</head>
<body>
    <h1 style="text-align:center;margin:30px 0 10px 0;">Danh sách sản phẩm</h1>
    <div class="products-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-item">
                    <img src="<?php echo htmlspecialchars($row['hinh_anh']); ?>" alt="<?php echo htmlspecialchars($row['ten_sp']); ?>">
                    <h3><?php echo htmlspecialchars($row['ten_sp']); ?></h3>
                    <p><b>Giá:</b> <?php echo number_format($row['gia_sp']); ?> VNĐ</p>
                    <?php if (isset($_SESSION['user'])): ?>
                    <a href="mua_hang.php?id=<?php echo $row['san_pham_id']; ?>" class="btn-muahang">Mua hàng</a>
                    <?php else: ?>
                    <a href="dangnhap.php" class="btn-muahang">Đăng nhập để mua</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="width:100%;text-align:center;">Không tìm thấy sản phẩm phù hợp.</p>
        <?php endif; ?>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>