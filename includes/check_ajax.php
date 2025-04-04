<?php
if (isset($_POST['page'])) {
    $page = basename($_POST['page']); // Lọc tên file tránh LFI

    $allowed_pages = ['home.php', 'shop.php', 'product.php', 'login.php', 'about.php'];

    if (in_array($page, $allowed_pages)) {
        $file_path = __DIR__ . "/../pages/$page";

        if (file_exists($file_path)) {
            if ($page === 'product.php' && isset($_POST['product_id'])) {
                $_GET['id'] = $_POST['product_id']; // Chuyển product_id thành GET để product.php xử lý
            }
            include $file_path;
        } else {
            echo "<p>File không tồn tại!</p>";
        }
    } else {
        echo "<p>Trang không hợp lệ!</p>";
    }
} else {
    echo "<p>Không có trang nào được chọn!</p>";
}
?>
