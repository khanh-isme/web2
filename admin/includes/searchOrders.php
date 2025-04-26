<?php
// --- Tệp: includes/searchOrders.php (Đã cập nhật để lọc địa chỉ) ---

include 'connect.php'; // Kết nối CSDL

header('Content-Type: application/json'); // Trả về JSON

$response = ['status' => 'error', 'message' => 'Invalid request.'];
$orders = [];
$params = []; // Mảng chứa các giá trị tham số cho bind_param
$types = '';  // Chuỗi chứa kiểu dữ liệu cho bind_param
$conditions = []; // Mảng chứa các điều kiện WHERE

// Chỉ xử lý nếu là POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Lấy các tham số tìm kiếm từ POST
    $searchOrderId = trim($_POST['search_order_id'] ?? '');
    $searchCustomerId = trim($_POST['search_customer_id'] ?? '');
    $searchStatus = trim($_POST['search_status'] ?? '');
    $searchDateFrom = trim($_POST['search_order_date_from'] ?? '');
    $searchDateTo = trim($_POST['search_order_date_to'] ?? '');
    $searchShippingName = trim($_POST['search_shipping_name'] ?? '');
    $searchShippingPhone = trim($_POST['search_shipping_phone'] ?? '');
    $searchShippingAddress = trim($_POST['search_shipping_address'] ?? ''); // <<< Lấy giá trị địa chỉ

    // --- Xây dựng mệnh đề WHERE động ---
    $sql = "SELECT order_id, customer_id, order_date, total_amount, status, shipping_name, shipping_phone, shipping_address FROM orders WHERE 1=1";

    // Thêm điều kiện lọc
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
    // --- THÊM LOGIC LỌC ĐỊA CHỈ ---
    if (!empty($searchShippingAddress)) {
       $conditions[] = "shipping_address LIKE ?"; // Sử dụng LIKE cho địa chỉ
       $params[] = "%" . $searchShippingAddress . "%"; // Thêm wildcard
       $types .= 's'; // Kiểu string
    }
    // --- KẾT THÚC LOGIC LỌC ĐỊA CHỈ ---


    // Nối các điều kiện vào câu SQL
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    // Sắp xếp kết quả
    $sql .= " ORDER BY order_date DESC";

    // --- Thực thi câu lệnh ---
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Gắn tham số nếu có
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

// Trả về kết quả JSON
echo json_encode($response);
exit();
?>