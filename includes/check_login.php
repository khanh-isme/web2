<?php
require_once 'config.php';
session_start();

// ✅ Nếu đã đăng nhập trước đó
if (isset($_SESSION['user'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => '<p><i class="fa-regular fa-circle-check green icon"></i>Đã đăng nhập!</p>',
        'username' => $_SESSION['user']['username'],
        'user_id' => $_SESSION['user']['id']
    ]);
    exit();
}

// ❌ Nếu chưa đăng nhập
header('Content-Type: application/json');
echo json_encode([
    'status' => 'error',
    'message' => '<p><i class="fa-solid fa-triangle-exclamation red icon"></i>Bạn chưa đăng nhập!</p>'
]);
exit();
