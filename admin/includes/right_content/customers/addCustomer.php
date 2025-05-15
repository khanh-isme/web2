<?php



include '../../connect.php';


header('Content-Type: application/json');


$response = ['status' => 'error', 'message' => 'Invalid request method.'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {



    $username = trim($_POST['username'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');


    $errors = [];
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }



    if (empty($errors)) {
        $sql_check = "SELECT id FROM users WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);

        if ($stmt_check) {
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $errors[] = "Email address already exists.";
            }
            $stmt_check->close();
        } else {

            $response['message'] = "Database error checking email: " . $conn->error;
            echo json_encode($response);
            $conn->close();
            exit();
        }
    }


    if (empty($errors)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        $phone_to_insert = ($phone === '') ? null : $phone;
        $address_to_insert = ($address === '') ? null : $address;


        $sql = "INSERT INTO users (username, name, email, password, phone, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("ssssss", $username, $name, $email, $hashed_password, $phone_to_insert, $address_to_insert);


            if ($stmt->execute()) {

                $response['status'] = 'success';
                $response['message'] = 'Customer added successfully!';
            } else {

                $response['message'] = "Error adding customer: " . $stmt->error;
            }
            $stmt->close();
        } else {

            $response['message'] = "Error preparing insert statement: " . $conn->error;
        }
    } else {

        $response['status'] = 'validation_error';
        $response['message'] = 'Please correct the errors below.';
        $response['errors'] = $errors;
    }
}


$conn->close();


echo json_encode($response);
exit();
