<?php
require_once 'config.php'; // File chứa thông tin kết nối DB
require_once 'getUser.php';

session_start();


// ✅ Xử lý khi gửi yêu cầu POST để đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    $user = getUser($username);
    if ($user != null) {
        if ($user['status'] === 'active') {
            // ⚠ Nếu bạn dùng password hash:
            // if (password_verify($password, $user['password'])) {
            if ($password === $user['password']) {
                $_SESSION['user'] = $user;

                $response = [
                    'status' => 'success',
                    'message' => '<p><i class="fa-regular fa-circle-check green icon"></i>Đăng nhập thành công!</p>',
                    'username' => $username,
                    'user_id' => $user['id'] 
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Tên đăng nhập hoặc mật khẩu không đúng!</p>',
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Tài khoản đã bị khóa!</p>',
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Tên đăng nhập hoặc mật khẩu không đúng!</p>',
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode([
        'status' => 'error',
        'message' => $_SERVER['REQUEST_METHOD']
    ]);
    exit();
}

$conn->close();
?>
