<?php
include '../../connect.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid Order ID.'];
$items = [];

$orderId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($orderId) {
    $sql = "SELECT od.quantity, od.price AS item_price, p.name AS product_name, ps.size AS product_size, p.image AS product_image
            FROM order_details od
            JOIN product_size ps ON od.product_size_id = ps.id
            JOIN products p ON ps.product_id = p.id
            WHERE od.order_id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $orderId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
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

echo json_encode($response);
exit();
