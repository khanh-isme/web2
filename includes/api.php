<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Kết nối database
$host = "127.0.0.1:3307";
$user = "root"; // Thay bằng user của bạn
$password = ""; // Thay bằng mật khẩu của bạn
$database = "shoe"; // Thay bằng tên database

$connection = new mysqli($host, $user, $password, $database);

// Kiểm tra kết nối
if ($connection->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $connection->connect_error]));
}




// Truy vấn dữ liệu
$sql = "SELECT id, name, price, description, image, category_id, collection_id, gender, created_at FROM products";
$result = $connection->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            "id" => $row["id"],
            "name" => $row["name"],
            "price" => number_format($row["price"], 0, ',', '.') . " VND",
            "description" => $row["description"] ?? '',
            "image" => $row["image"] ?? 'default.jpg',
            "category_id" => $row["category_id"],
            "collection_id" => $row["collection_id"],
            "gender" => $row["gender"],
            "created_at" => date('c', strtotime($row["created_at"])) // ISO 8601
        ];
    }
}

// Đóng kết nối
$connection->close();

// Trả về JSON
echo json_encode(["products" => $products], JSON_PRETTY_PRINT);
?>
