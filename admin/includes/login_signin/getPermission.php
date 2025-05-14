<?php
    require_once("../connect.php");
    function getPermissions($username) {
        global $conn;
        
        // Truy vấn SQL
        $sql = "
            SELECT p.permission
            FROM permissions p
            JOIN admin_permissions ap ON p.id = ap.perm_id
            JOIN admins a ON ap.admin_id = a.id
            WHERE a.username = ?
        ";
    
        // Chuẩn bị và bind tham số
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
    
            // Lấy kết quả truy vấn
            $result = $stmt->get_result();
    
            // Sử dụng fetch_all để lấy tất cả dữ liệu vào một mảng
            $permissionsArray = $result->fetch_all(MYSQLI_ASSOC);
    
            // Chỉ lấy các giá trị perm_name và trả về một mảng mới
            $permissions = array_column($permissionsArray, 'permission');
    
            // Đóng câu lệnh
            $stmt->close();
    
            // Trả về mảng quyền
            return $permissions;
        } else {
            echo "Error: " . $conn->error;
            return [];
        }
    }
?>