<?php
include '../../connect.php'; 

header('Content-Type: application/json'); 

$response = ['status' => 'error', 'message' => 'Invalid Request.'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {

    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? ''); 
    $address = trim($_POST['address'] ?? ''); 

    $errors = [];

    if ($id === false || $id <= 0) {
        $errors[] = "Invalid customer ID.";
    }
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($errors)) {
        $sql_check = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt_check = $conn->prepare($sql_check);
        if ($stmt_check) {
            $stmt_check->bind_param("si", $email, $id); 
            $stmt_check->execute();
            $stmt_check->store_result(); 
            if ($stmt_check->num_rows > 0) {
                $errors[] = "Email address is already used by another customer.";
            }
            $stmt_check->close();
        } else {
            $errors[] = "Database error checking email: " . $conn->error;
        }
    }

    if (empty($errors)) { 
        $sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            
            $phone_to_update = ($phone === '') ? null : $phone;
            $address_to_update = ($address === '') ? null : $address;

            $stmt->bind_param("ssssi", $name, $email, $phone_to_update, $address_to_update, $id);

            if ($stmt->execute()) {
                
                if ($stmt->affected_rows > 0) {
                    $response['status'] = 'success';
                    $response['message'] = '<p><i class="fa-regular fa-circle-check green icon"></i>Customer updated successfully!</p>';
                    
                    $response['updated_data'] = [
                        'id' => $id,
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone_to_update ?? '', 
                        'address' => $address_to_update ?? ''
                    ];
                } else {
                    $response['status'] = 'info'; 
                    $response['message'] = '<p><i class="fa-regular fa-circle-check green icon"></i>No changes were made to the customer.</p>';
                }
            } else {
                
                $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Error updating customer: </p>' . $stmt->error;
                $response['sql_error'] = $stmt->error;
            }
            $stmt->close();
        } else {
            
            $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Error preparing update statement: </p>' . $conn->error;
            $response['sql_error'] = $conn->error;
        }
    } else {
        $response['status'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>validation_error</p>';
        $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Validation failed.</p>';
        $response['errors'] = $errors;
    }
} else {
    $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Invalid request method or missing ID.</p>';
}

$conn->close();
echo json_encode($response);
exit();
