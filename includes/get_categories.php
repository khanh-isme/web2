<?php
// Kết nối database
require_once "./config.php"; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Hàm lấy danh sách categories
function getCategoryList($connection) {
    $strSQL = "SELECT * FROM categories";
    $result = $connection->query($strSQL);

    $categoryList = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categoryList[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description']
            );
        }
    }
    return $categoryList;
}

// Lấy danh sách categories
$categories = getCategoryList($connection);

// Trả về dữ liệu dưới dạng JSON
echo json_encode($categories, JSON_UNESCAPED_UNICODE);
?>
