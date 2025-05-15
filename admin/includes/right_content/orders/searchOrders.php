<?php
require_once '../../connect.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request.'];
$orders = [];
$params = [];
$types = '';
$conditions = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $searchOrderId = trim($_POST['search_order_id'] ?? '');
    $searchCustomerId = trim($_POST['search_customer_id'] ?? '');
    $searchStatus = trim($_POST['search_status'] ?? '');
    $searchDateFrom = trim($_POST['search_order_date_from'] ?? '');
    $searchDateTo = trim($_POST['search_order_date_to'] ?? '');
    $searchShippingName = trim($_POST['search_shipping_name'] ?? '');
    $searchShippingPhone = trim($_POST['search_shipping_phone'] ?? '');
    $searchShippingAddress = trim($_POST['search_shipping_address'] ?? '');

    $sql = "SELECT order_id, customer_id, order_date, total_amount, status, shipping_name, shipping_phone, shipping_address FROM orders WHERE 1=1";

    if (!empty($searchOrderId) && is_numeric($searchOrderId)) {
        $conditions[] = "order_id = ?";
        $params[] = $searchOrderId;
        $types .= 'i';
    }
    if (!empty($searchCustomerId) && is_numeric($searchCustomerId)) {
        $conditions[] = "customer_id = ?";
        $params[] = $searchCustomerId;
        $types .= 'i';
    }
    if (!empty($searchStatus)) {
        $conditions[] = "status = ?";
        $params[] = $searchStatus;
        $types .= 's';
    }
    if (!empty($searchDateFrom)) {
        $conditions[] = "order_date >= ?";
        $params[] = $searchDateFrom . ' 00:00:00';
        $types .= 's';
    }
    if (!empty($searchDateTo)) {
        $conditions[] = "order_date <= ?";
        $params[] = $searchDateTo . ' 23:59:59';
        $types .= 's';
    }
    if (!empty($searchShippingName)) {
        $conditions[] = "shipping_name LIKE ?";
        $params[] = "%" . $searchShippingName . "%";
        $types .= 's';
    }
    if (!empty($searchShippingPhone)) {
        $conditions[] = "shipping_phone LIKE ?";
        $params[] = "%" . $searchShippingPhone . "%";
        $types .= 's';
    }
    if (!empty($searchShippingAddress)) {
        $conditions[] = "shipping_address LIKE ?";
        $params[] = "%" . $searchShippingAddress . "%";
        $types .= 's';
    }
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }
    $sql .= " ORDER BY order_date DESC";

    $stmt = $conn->prepare($sql);

    if ($stmt) {

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $row['shipping_phone'] = $row['shipping_phone'] ?? '';
                $row['shipping_address'] = $row['shipping_address'] ?? '';
                $orders[] = $row;
            }
            $response['status'] = 'success';
            $response['data'] = $orders;
            $response['message'] = 'Search completed.';
        } else {
            $response['message'] = "Error executing search query: " . $stmt->error;
            $response['sql_error'] = $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Error preparing search statement: " . $conn->error;
        $response['sql_error'] = $conn->error;
        $response['debug_sql'] = $sql;
    }
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();

echo json_encode($response);
exit();
