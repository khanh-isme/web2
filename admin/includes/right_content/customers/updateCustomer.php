<?php
// --- Tệp: includes/updateCustomer.php ---

// session_start(); // Optional: Start session for flash messages
include '../../connect.php'; // Đảm bảo đường dẫn này chính xác

header('Content-Type: application/json'); // Luôn trả về JSON

$response = ['status' => 'error', 'message' => 'Invalid Request.'];

// Check if the form was submitted and ID is present via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {

    // --- Basic Input Validation ---
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? ''); // Giữ chuỗi rỗng nếu không có
    $address = trim($_POST['address'] ?? ''); // Giữ chuỗi rỗng nếu không có

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
    // Optional: Add more validation for phone, etc.

    // Check if email already exists for *another* user (important!)
    if (empty($errors)) {
        $sql_check = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt_check = $conn->prepare($sql_check);
        if ($stmt_check) {
            $stmt_check->bind_param("si", $email, $id); // Bind email and the *current* user's ID
            $stmt_check->execute();
            $stmt_check->store_result(); // Store result to check num_rows
            if ($stmt_check->num_rows > 0) {
                $errors[] = "Email address is already used by another customer.";
            }
            $stmt_check->close();
        } else {
            $errors[] = "Database error checking email: " . $conn->error;
        }
    }

    // --- Process Update if no errors ---
    if (empty($errors)) {
        // Prepare SQL statement to prevent SQL injection
        // Note: Password is NOT updated here. Typically, password updates are handled separately.
        $sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Use null for phone/address if they are empty strings after trimming and DB allows NULL
            $phone_to_update = ($phone === '') ? null : $phone;
            $address_to_update = ($address === '') ? null : $address;

            // Bind parameters (s = string, i = integer)
            $stmt->bind_param("ssssi", $name, $email, $phone_to_update, $address_to_update, $id);

            // Execute the statement
            if ($stmt->execute()) {
                // Check if any row was actually updated
                if ($stmt->affected_rows > 0) {
                    $response['status'] = 'success';
                    $response['message'] = "Customer updated successfully!";
                    // Trả về dữ liệu đã cập nhật để JS có thể cập nhật bảng
                    $response['updated_data'] = [
                        'id' => $id,
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone_to_update ?? '', // Gửi về giá trị đã chuẩn hóa
                        'address' => $address_to_update ?? ''
                    ];
                } else {
                    $response['status'] = 'info'; // Dùng info nếu không có gì thay đổi
                    $response['message'] = "No changes were made to the customer.";
                }
            } else {
                // Error during execution
                $response['message'] = "Error updating customer: " . $stmt->error;
                $response['sql_error'] = $stmt->error;
            }
            $stmt->close();
        } else {
            // Error preparing statement
            $response['message'] = "Error preparing update statement: " . $conn->error;
            $response['sql_error'] = $conn->error;
        }
    } else {
        // Validation errors occurred
        $response['status'] = 'validation_error';
        $response['message'] = 'Validation failed.';
        $response['errors'] = $errors;
    }
} else {
    $response['message'] = 'Invalid request method or missing ID.';
}

$conn->close();
echo json_encode($response);
exit();
