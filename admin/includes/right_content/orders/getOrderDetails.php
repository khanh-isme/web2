<?php
// --- Tệp: includes/getOrderDetails.php ---

include '../../connect.php'; // Kết nối CSDL

header('Content-Type: application/json'); // Trả về JSON

$response = ['status' => 'error', 'message' => 'Invalid Order ID.'];
$items = [];

// Lấy order_id từ tham số GET (an toàn hơn là POST cho việc lấy dữ liệu)
$orderId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($orderId) {
    // Chuẩn bị câu lệnh SQL để lấy chi tiết đơn hàng
    // JOIN với product_size và products để lấy tên sản phẩm và size
    $sql = "SELECT od.quantity, od.price AS item_price, p.name AS product_name, ps.size AS product_size, p.image AS product_image
            FROM order_details od
            JOIN product_size ps ON od.product_size_id = ps.id
            JOIN products p ON ps.product_id = p.id
            WHERE od.order_id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $orderId); // Bind order_id (integer)

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                // Xử lý đường dẫn ảnh nếu cần (ví dụ: thêm tiền tố nếu chưa có)
                // $row['product_image'] = ...;
                $items[] = $row;
            }
            $response['status'] = 'success';
            $response['data'] = $items;
            $response['message'] = 'Order items fetched successfully.';
        } else {
            $response['message'] = "Error executing query: " . $stmt->error;
            $response['sql_error'] = $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Error preparing statement: " . $conn->error;
        $response['sql_error'] = $conn->error;
    }
} else {
     $response['message'] = 'Valid Order ID is required.';
}

$conn->close();

// Trả về kết quả JSON
echo json_encode($response);
exit();
?>