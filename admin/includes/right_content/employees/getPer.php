<?php
require_once("../../connect.php");
header('Content-Type: application/json');

$adminId = isset($_GET['admin_id']) ? intval($_GET['admin_id']) : 0;

$allPermissions = [];

$permResult = $conn->query("SELECT id, permission FROM permissions");
while ($row = $permResult->fetch_assoc()) {
    $allPermissions[$row['id']] = [
        'id' => $row['id'],
        'name' => $row['permission'],
        'hasPermission' => false,
        'source' => null
    ];
}

if ($adminId > 0) {
    $stmt = $conn->prepare("
        SELECT rp.perm_id 
        FROM admins
        JOIN roles on roles.role_name = admins.role
        JOIN roles_permissions rp ON roles.id = rp.role_id
        WHERE admins.id = ?
    ");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $permId = $row['perm_id'];
        if (isset($allPermissions[$permId])) {
            $allPermissions[$permId]['source'] = 'role'; 
        }
    }
    $stmt->close();

    $stmt = $conn->prepare("SELECT perm_id FROM admin_permissions WHERE admin_id = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $permId = $row['perm_id'];
        if (isset($allPermissions[$permId])) {
            $allPermissions[$permId]['hasPermission'] = true;

            if ($allPermissions[$permId]['source'] === null) {
                $allPermissions[$permId]['source'] = 'direct';
            }
        }
    }
    $stmt->close();
}

$allPermissionsList = array_values($allPermissions);

echo json_encode([
    'success' => true,
    'permissions' => $allPermissionsList
]);

$conn->close();
?>
