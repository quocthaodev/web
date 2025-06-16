<?php
session_start();
require_once "connect.php";
if (!isset($_SESSION['user'])) {
    header("Location: dangnhap.php");
    exit();
}
$user = $_SESSION['user'];
$sql = "SELECT dh.*, sp.ten_sp, sp.hinh_anh FROM don_hang dh
        JOIN san_pham sp ON dh.san_pham_id = sp.san_pham_id
        WHERE dh.username = ?
        ORDER BY dh.ngay_dat DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
?>
<?php include "header.php"; ?>
<div class="container" style="max-width:900px;margin:40px auto;">
    <h2 style="text-align:center;margin-bottom:24px;">Đơn hàng của bạn</h2>
    <table style="width:100%;background:#fff;border-radius:10px;">
        <tr style="background:#f3f4f6;">
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Ngày đặt</th>
            <th>Trạng thái</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><img src="<?php echo htmlspecialchars($row['hinh_anh']); ?>" alt="" width="60"></td>
            <td><?php echo htmlspecialchars($row['ten_sp']); ?></td>
            <td><?php echo $row['ngay_dat']; ?></td>
            <td><?php echo htmlspecialchars($row['trangthai']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include "footer.php"; ?>