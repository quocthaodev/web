<?php
session_start();
require_once "connect.php";
include "header.php";
if (!isset($_SESSION['user'])) {
    header("Location: dangnhap.php");
    exit();
}

// Lấy id sản phẩm từ URL
$id_sp = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM san_pham WHERE san_pham_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_sp);
$stmt->execute();
$sp = $stmt->get_result()->fetch_assoc();

$khachhang = $_SESSION['user'];
$bank_name = "Ngân hàng Vietcombank";
$bank_account = "0123456789";
$bank_owner = "Nguyễn Văn A";
$thongbao = "";

// Xử lý đặt hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dathang'])) {
    $ten_kh = trim($_POST['ten_kh']);
    $sdt = $_POST['sdt'];
    $diachi = $_POST['diachi'];
    $ghichu = $_POST['ghichu'];
    $pttt = $_POST['pttt'];
    $trangthai = "Đã đặt hàng";

    // Lưu đơn hàng vào DB (bảng don_hang phải có cột ten_kh)
    $sql_donhang = "INSERT INTO don_hang (username, ten_kh, san_pham_id, sdt, diachi, ghichu, pttt, trangthai, ngay_dat)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt_dh = $conn->prepare($sql_donhang);
    $stmt_dh->bind_param("ssisssss", $khachhang, $ten_kh, $id_sp, $sdt, $diachi, $ghichu, $pttt, $trangthai);
    $stmt_dh->execute();

    // Xóa sản phẩm khỏi giỏ hàng session nếu có
    if (isset($_SESSION['cart'][$id_sp])) {
        unset($_SESSION['cart'][$id_sp]);
    }

    // Thông báo và chuyển hướng về giỏ hàng
    header("Location: gio_hang.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mua_hang.css">
</head>
<body>
    <div class="order-container">
        <h2>Xác nhận đơn hàng</h2>
        <?php if ($thongbao): ?>
            <div class="tb"><?php echo $thongbao; ?></div>
        <?php endif; ?>
        <form class="order-form" method="post" action="">
            <label>Tên khách hàng</label>
            <input type="text" name="ten_kh" placeholder="Nhập tên khách hàng" required>
            <label>Số điện thoại</label>
            <input type="text" name="sdt" placeholder="Nhập số điện thoại" required>
            <label>Địa chỉ nhận hàng</label>
            <textarea name="diachi" rows="2" placeholder="Nhập địa chỉ nhận hàng" required></textarea>
            <label>Ghi chú</label>
            <textarea name="ghichu" rows="2" placeholder="Ghi chú thêm (nếu có)"></textarea>
            <label>Phương thức thanh toán</label>
            <div class="pay-methods">
                <label><input type="radio" name="pttt" value="vnpay" required> Thanh toán qua VNPAY</label>
                <label><input type="radio" name="pttt" value="momo"> Thanh toán qua Ví điện tử MoMo</label>
                <label><input type="radio" name="pttt" value="cod"> Thanh toán khi giao hàng (COD)</label>
            </div>
            <div class="order-policy">
                *QUÝ KHÁCH ĐÃ ĐỌC VÀ ĐỒNG Ý VỚI CÁC CHÍNH SÁCH VÀ ĐIỀU KHOẢN CỦA VIETMAP: 
                <a href="https://vietmap.vn/chinh-sach-chung-vietmap" target="_blank">https://vietmap.vn/chinh-sach-chung-vietmap</a>
            </div>
            <div class="order-summary">
                <b>Đơn hàng (1 sản phẩm)</b>
                <table>
                    <tr>
                        <th>Ảnh sản phẩm</th>
                        <th>Mô tả</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                    </tr>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($sp['hinh_anh']); ?>" alt=""></td>
                        <td>
                            <?php echo htmlspecialchars($sp['ten_sp']); ?><br>
                            RAM 4GB ROM 32 GB
                        </td>
                        <td>1</td>
                        <td><?php echo number_format($sp['gia_sp']); ?>₫</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;">Tạm tính</td>
                        <td><?php echo number_format($sp['gia_sp']); ?>₫</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;">Phí vận chuyển</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;" class="total">Tổng cộng</td>
                        <td class="total"><?php echo number_format($sp['gia_sp']); ?>₫</td>
                    </tr>
                </table>
            </div>
            <button type="submit" name="dathang">ĐẶT HÀNG</button>
        </form>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>