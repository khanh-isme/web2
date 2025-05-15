<?php
require_once("../connect.php");
function getPermissions($username)
{
    global $conn;


    $sql = "
            SELECT p.permission
            FROM permissions p
            JOIN admin_permissions ap ON p.id = ap.perm_id
            JOIN admins a ON ap.admin_id = a.id
            WHERE a.username = ?
        ";


    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        $permissionsArray = $result->fetch_all(MYSQLI_ASSOC);

        $permissions = array_column($permissionsArray, 'permission');

        $stmt->close();

        return $permissions;
    } else {
        echo "Error: " . $conn->error;
        return [];
    }
}
