<?php
session_start();         // Bắt đầu session (để truy cập $_SESSION)
session_unset();         // Xóa tất cả biến session hiện tại
session_destroy();       // Hủy toàn bộ session (xóa session trên server)
header("Location: index.php"); // Chuyển hướng về trang chủ
exit();                 // Kết thúc script