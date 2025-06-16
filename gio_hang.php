<?php
session_start();
require_once "connect.php";

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xóa sản phẩm khỏi giỏ
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$remove_id]);
    header("Location: gio_hang.php");
    exit();
}

// Cập nhật số lượng sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $qty = max(1, intval($qty));
        $_SESSION['cart'][$id] = $qty;
    }
    header("Location: gio_hang.php");
    exit();
}

// Lấy thông tin sản phẩm trong giỏ
$cart_items = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $sql = "SELECT * FROM san_pham WHERE san_pham_id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $row['qty'] = $_SESSION['cart'][$row['san_pham_id']];
        $row['thanhtien'] = $row['qty'] * $row['gia_sp'];
        $cart_items[] = $row;
        $total += $row['thanhtien'];
    }
}
?>
<?php include "header.php"; ?>
<h2 style="text-align:center;margin:40px 0 24px 0;font-size:30px;">Đơn hàng đã mua</h2>
<div class="container" style="max-width:900px;margin:40px auto;">
    <?php if (!empty($cart_items)): ?>
        <h2 style="text-align:center;margin:40px 0 24px 0;">Giỏ hàng của bạn</h2>
        <form method="post" action="">
            <table style="width:100%;background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
                <tr style="background:#f3f4f6;">
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Xóa</th>
                    <th>Đặt hàng</th>
                </tr>
                <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($item['hinh_anh']); ?>" alt="" width="60"></td>
                    <td><?php echo htmlspecialchars($item['ten_sp']); ?></td>
                    <td><?php echo number_format($item['gia_sp']); ?>₫</td>
                    <td>
                        <input type="number" name="qty[<?php echo $item['san_pham_id']; ?>]" value="<?php echo $item['qty']; ?>" min="1" style="width:60px;">
                    </td>
                    <td><?php echo number_format($item['thanhtien']); ?>₫</td>
                    <td>
                        <a href="gio_hang.php?remove=<?php echo $item['san_pham_id']; ?>" style="color:#ef4444;" onclick="return confirm('Xóa sản phẩm này khỏi giỏ?')">Xóa</a>
                    </td>
                    <td>
                        <a href="mua_hang.php?id=<?php echo $item['san_pham_id']; ?>" style="padding:6px 12px;background:#22c55e;color:#fff;border-radius:6px;text-decoration:none;">Đặt hàng</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" style="text-align:right;font-weight:bold;">Tổng cộng:</td>
                    <td colspan="3" style="color:#7c3aed;font-weight:bold;"><?php echo number_format($total); ?>₫</td>
                </tr>
            </table>
            <div style="margin:18px 0;text-align:right;">
                <button type="submit" name="update" style="padding:8px 18px;background:#7c3aed;color:#fff;border:none;border-radius:6px;cursor:pointer;">Cập nhật giỏ hàng</button>
            </div>
        </form>
    <?php else: ?>
        <?php
        if (isset($_SESSION['user'])) {
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
            <table style="width:100%;background:#fff;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,0.06);margin-bottom:48px;">
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
        <?php } else { ?>
            <p style="text-align:center;">Bạn chưa đăng nhập.</p>
        <?php } ?>
    <?php endif; ?>
</div>
<?php include "footer.php"; ?>