<?php
    session_start();
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    
    $response = [
        'message' => '<p><i class="fa-regular fa-circle-check green icon"></i>Đăng xuất thành công!</p>',
        'html' => file_get_contents("../login_signin/admin_login.php"),
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
?>