<?php
include '../../connect.php'; 

header('Content-Type: application/json'); 

$response = ['status' => 'error', 'message' => 'Invalid request.'];
$customers = [];
$params = []; 
$types = '';  
$conditions = []; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    $searchId = trim($_POST['search_id'] ?? '');
    $searchUsername = trim($_POST['search_username'] ?? '');
    $searchName = trim($_POST['search_name'] ?? '');
    $searchEmail = trim($_POST['search_email'] ?? '');
    $searchPhone = trim($_POST['search_phone'] ?? '');
    $searchAddress = trim($_POST['search_address'] ?? '');

    $sql = "SELECT id, username, name, email, phone, address FROM users WHERE (status = 'active' or status = 'locked')"; 

    if (!empty($searchId) && is_numeric($searchId)) {
        $conditions[] = "id = ?";
        $params[] = $searchId;
        $types .= 'i'; 
    }
    if (!empty($searchUsername)) {
        $conditions[] = "username LIKE ?";
        $params[] = "%" . $searchUsername . "%"; 
        $types .= 's'; 
    }
    if (!empty($searchName)) {
        $conditions[] = "name LIKE ?";
        $params[] = "%" . $searchName . "%"; 
        $types .= 's'; 
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

    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY name ASC"; 

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
    $response['message'] = 'Invalid request method.'; 
}

$conn->close();

echo json_encode($response);
exit();
