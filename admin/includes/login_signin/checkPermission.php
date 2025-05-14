<?php
    session_start();
    header('Content-Type: application/json');

    require_once 'getPermission.php';

    // Giả sử danh sách quyền được lưu trong session
    $permissions = getPermissions($_SESSION['user']['username']);

    $data = json_decode(file_get_contents('php://input'), true);
    $requestedPermission = $data['permission'] ?? '';

    $response = [
        'allowed' => in_array($requestedPermission, $permissions)
    ];

    echo json_encode($response);
?>