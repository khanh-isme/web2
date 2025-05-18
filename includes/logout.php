<?php
session_start();

// Xoá tất cả session
$_SESSION = [];

// Hủy session
session_destroy();

// Trả về JSON cho client
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => '<p><i class="fa-regular fa-circle-check green icon"></i>Đăng xuất thành công!</p>'
]);
exit();
?>
