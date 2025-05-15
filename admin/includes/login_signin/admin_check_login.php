<?php
session_start();
require_once("admin_check_exist.php");
require_once("getPermission.php");
require_once("getUser.php");
if (isset($_SESSION['user']) && checkUserExist($_SESSION['user']['username'])) {
    $user = getUser($_SESSION['user']['username']);
    require_once('../responseHTML.php');
    $perms = getPermissions($_SESSION['user']['username']);

    if ($user['status'] === 'active') {
        $response = [
            'status' => 'success',
            'message' => '<p><i class="fa-regular fa-circle-check green icon"></i>Chào mừng trở lại, ' . htmlspecialchars($user['fullname'], ENT_QUOTES, 'UTF-8') . ' huynh!</p>',
            'html' => responseHTML($perms, $user),
            'default' => !empty($perms) ? $perms[0] : '',
        ];
    } else {
        session_destroy();
        if ($user['status'] === 'inactive') {
            $response = [
                'status' => 'error',
                'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Tài khoản ' . htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . ' đã bị khóa!</p>',
                'html' => file_get_contents("admin_login.php"),
                'default' => '',
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Tài khoản ' . htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . ' không tồn tại!</p>',
                'html' => file_get_contents("admin_login.php"),
                'default' => '',
            ];
        }
    }
} else {
    session_destroy();
    $response = [
        'status' => 'error',
        'message' => '',
        'html' => file_get_contents("admin_login.php"),
        'default' => '',
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
