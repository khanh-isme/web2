<?php
// --- Tệp: includes/searchCustomers.php ---

include '../../connect.php'; // Kết nối CSDL

header('Content-Type: application/json'); // Trả về JSON

$response = ['status' => 'error', 'message' => 'Invalid request.'];
$customers = [];
$params = []; // Mảng chứa các giá trị tham số cho bind_param
$types = '';  // Chuỗi chứa kiểu dữ liệu cho bind_param
$conditions = []; // Mảng chứa các điều kiện WHERE

// Chỉ xử lý nếu là POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // <<< Kiểm tra POST

    // Lấy các tham số tìm kiếm từ POST
    $searchId = trim($_POST['search_id'] ?? '');
    $searchUsername = trim($_POST['search_username'] ?? '');
    $searchName = trim($_POST['search_name'] ?? '');
    $searchEmail = trim($_POST['search_email'] ?? '');
    $searchPhone = trim($_POST['search_phone'] ?? '');
    $searchAddress = trim($_POST['search_address'] ?? '');

    // --- Xây dựng mệnh đề WHERE động ---
    $sql = "SELECT id, username, name, email, phone, address FROM users WHERE (status = 'active' or status = 'locked')"; // Chỉ lấy khách hàng đang hoạt động

    if (!empty($searchId) && is_numeric($searchId)) {
        $conditions[] = "id = ?";
        $params[] = $searchId;
        $types .= 'i'; // integer
    }
    if (!empty($searchUsername)) {
        $conditions[] = "username LIKE ?";
        $params[] = "%" . $searchUsername . "%"; // Thêm wildcard
        $types .= 's'; // string
    }
    if (!empty($searchName)) {
        $conditions[] = "name LIKE ?";
        $params[] = "%" . $searchName . "%"; // Thêm wildcard
        $types .= 's'; // string
    }
    if (!empty($searchEmail)) {
        $conditions[] = "email LIKE ?";
        $params[] = "%" . $searchEmail . "%";
        $types .= 's';
    }
    if (!empty($searchPhone)) {
        $conditions[] = "phone LIKE ?";
        $params[] = "%" . $searchPhone . "%";
        $types .= 's';
    }
    if (!empty($searchAddress)) {
        $conditions[] = "address LIKE ?";
        $params[] = "%" . $searchAddress . "%";
        $types .= 's';
    }

    // Nối các điều kiện vào câu SQL
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY name ASC"; // Thêm ORDER BY

    // --- Thực thi câu lệnh ---
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Gắn tham số nếu có
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params); // Sử dụng toán tử spread (...)
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $row['phone'] = $row['phone'] ?? '';
                $row['address'] = $row['address'] ?? '';
                $customers[] = $row;
            }
            $response['status'] = 'success';
            $response['data'] = $customers;
            $response['message'] = 'Search completed.';
            $response['sql'] = $sql;
        } else {
            $response['message'] = "Error executing search query: " . $stmt->error;
            $response['sql_error'] = $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Error preparing search statement: " . $conn->error;
        $response['sql_error'] = $conn->error;
        $response['sql_query'] = $sql;
    }
} else {
    $response['message'] = 'Invalid request method.'; // <<< Lỗi nếu không phải POST
}

$conn->close();

// Trả về kết quả JSON
echo json_encode($response);
exit();
