<?php
// filepath: e:\xampp\htdocs\website\index.php
session_start();
require_once "connect.php";

// Xử lý tìm kiếm sản phẩm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
include "header.php";
?>
<div class="container">
    <aside>
        <h3>Danh mục</h3>
        <ul>
            <li><a href="#">Mục 1</a></li>
            <li><a href="#">Mục 2</a></li>
            <li><a href="#">Mục 3</a></li>
            <li><a href="#">Mục 4</a></li>
        </ul>
        <div class="social">
            <h4>Kết nối</h4>
            <a href="#"><img src="hinh_anh/facebook.png" alt="Facebook" width="24"></a>
            <a href="#"><img src="hinh_anh/zalo.png" alt="Zalo" width="24"></a>
            <a href="#"><img src="hinh_anh/instagram.png" alt="Instagram" width="24"></a>
        </div>
    </aside>
    <main>
        <section class="banner"> 
            <img src="hinh_anh/banner.WEBP" alt="Banner" style="width:100%;max-height:250px;object-fit:cover;">
        </section>
        <section>
            <h1>Chào mừng đến với Trang web của tôi</h1> 
            <p>Chúng tôi cung cấp các sản phẩm và dịch vụ chất lượng, uy tín, giá cả hợp lý.</p>
        </section>
        <section>
            <h2>Sản phẩm nổi bật</h2>
            <div class="products">
                <?php
                // Nếu có tìm kiếm thì lọc theo tên sản phẩm
                if (!empty($search)) {       //Kiểm tra xem biến có giá trị "không rỗng" hay không.                            
                    $sql_noibat = "SELECT * FROM san_pham WHERE ten_sp LIKE ? ORDER BY san_pham_id DESC";
                    $stmt = $conn->prepare($sql_noibat);
                    $like = "%$search%";         
                    $stmt->bind_param("s", $like);  // Gắn biến vào dấu hỏi chấm ?, s là: string
                    $stmt->execute(); //Thực thi câu lệnh SQL đã chuẩn bị và gắn dữ liệu
                    $result_noibat = $stmt->get_result();
                } else {
                    $sql_noibat = "SELECT * FROM san_pham ORDER BY san_pham_id DESC LIMIT 3";
                    $result_noibat = $conn->prepage($sql_noibat);
                }
                if ($result_noibat && $result_noibat->num_rows > 0) {
                    while($sp = $result_noibat->fetch_assoc()) { //fetch-assoc Lấy 1 dòng kết quả dưới dạng mảng key là tên cột
                        echo '<div class="product">  //dùng để mã hóa ký tự cho an toàn
                                <img src="'.htmlspecialchars($sp["hinh_anh"]).'" alt="'.htmlspecialchars($sp["ten_sp"]).'" width="120">
                                <h3>'.htmlspecialchars($sp["ten_sp"]).'</h3>
                                <p><b>Giá:</b> '.number_format($sp["gia_sp"]).' VNĐ</p>';
                           if (isset($_SESSION['user'])) {
                            echo '<a href="mua_hang.php?id='.htmlspecialchars($sp["san_pham_id"]).'" class="btn-muahang">Mua hàng</a>';
                        } else {
                            echo '<a href="dangnhap.php" class="btn-muahang">Đăng nhập để mua</a>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p>Không tìm thấy sản phẩm phù hợp.</p>';
                }
                ?>
            </div>
        </section>
    </main>
</div>
<?php include "footer.php"; ?>
</body>
</html>