<?php
include '../../connect.php'; 

header('Content-Type: application/json'); 

$response = ['status' => 'error', 'message' => 'Invalid request.'];
$customers = [];
$params = []; 
$types = '';  
$base_conditions = []; // Sử dụng một mảng cho các điều kiện cơ bản (ví dụ: không phải là 'deleted' nếu không có filter)
$filter_conditions = []; // Sử dụng mảng riêng cho các điều kiện từ filter

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    $searchId = trim($_POST['search_id'] ?? '');
    $searchUsername = trim($_POST['search_username'] ?? '');
    $searchName = trim($_POST['search_name'] ?? '');
    $searchEmail = trim($_POST['search_email'] ?? '');
    $searchPhone = trim($_POST['search_phone'] ?? '');
    $searchAddress = trim($_POST['search_address'] ?? '');
    $searchStatus = trim($_POST['search_status'] ?? ''); // Lấy giá trị status từ form

    // Câu lệnh SQL cơ bản, lấy tất cả các trường cần thiết bao gồm cả status
    $sql = "SELECT id, username, name, email, phone, address, status FROM users WHERE status != 'deleted'"; 
    
    // Điều kiện mặc định (có thể bạn muốn chỉ hiển thị active và locked nếu không có filter status cụ thể)
    // Tuy nhiên, nếu có search_status, chúng ta sẽ dùng nó.
    // Nếu search_status rỗng (All Statuses), thì không thêm điều kiện status cụ thể vào $filter_conditions
    // hoặc có thể bạn muốn một logic khác ở đây, ví dụ: nếu search_status rỗng thì chỉ lấy active và locked.
    // Hiện tại, nếu search_status rỗng, nó sẽ lấy tất cả.

    if (!empty($searchId) && is_numeric($searchId)) {
        $filter_conditions[] = "id = ?";
        $params[] = $searchId;
        $types .= 'i'; 
    }
    if (!empty($searchUsername)) {
        $filter_conditions[] = "username LIKE ?";
        $params[] = "%" . $searchUsername . "%"; 
        $types .= 's'; 
    }
    if (!empty($searchName)) {
        $filter_conditions[] = "name LIKE ?";
        $params[] = "%" . $searchName . "%"; 
        $types .= 's'; 
    }
    if (!empty($searchEmail)) {
        $filter_conditions[] = "email LIKE ?";
        $params[] = "%" . $searchEmail . "%";
        $types .= 's';
    }
    if (!empty($searchPhone)) {
        $filter_conditions[] = "phone LIKE ?";
        $params[] = "%" . $searchPhone . "%";
        $types .= 's';
    }
    if (!empty($searchAddress)) {
        $filter_conditions[] = "address LIKE ?";
        $params[] = "%" . $searchAddress . "%";
        $types .= 's';
    }
    // Thêm điều kiện cho status nếu nó được chọn từ form
    if (!empty($searchStatus)) {
        $filter_conditions[] = "status = ?";
        $params[] = $searchStatus;
        $types .= 's';
    }

    // Nối các điều kiện
    if (!empty($base_conditions) || !empty($filter_conditions)) {
        $all_conditions = array_merge($base_conditions, $filter_conditions);
        $sql .= ' AND ' . implode(" AND ", $all_conditions);
    }

    $sql .= " ORDER BY id ASC"; 

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params); 
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $row['phone'] = $row['phone'] ?? '';
                $row['address'] = $row['address'] ?? '';
                // $row['status'] đã được SELECT và sẽ là giá trị từ DB
                $customers[] = $row;
            }
            $response['status'] = 'success';
            $response['data'] = $customers;
            $response['message'] = 'Search completed.';
        } else {
            $response['message'] = "Error executing search query: " . $stmt->error;
            $response['sql_error'] = $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Error preparing search statement: " . $conn->error;
        $response['sql_error'] = $conn->error;
        // $response['sql_query_debug'] = $sql; // Bỏ comment để debug SQL nếu cần
    }
} else {
    $response['message'] = 'Invalid request method.'; 
}

$conn->close();

echo json_encode($response);
exit();