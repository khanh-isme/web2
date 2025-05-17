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

            $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Database error checking email: </p>' . $conn->error;
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

                $response['status'] = '<p><i class="fa-regular fa-circle-check green icon"></i>success</p>';
                $response['message'] = '<p><i class="fa-regular fa-circle-check green icon"></i>Customer added successfully!</p>';
            } else {

                $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Error adding customer: </p>' . $stmt->error;
            }
            $stmt->close();
        } else {

            $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Error preparing insert statement: </p>' . $conn->error;
        }
    } else {

        $response['status'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>validation_error</p>';
        $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Please correct the errors below.</p>';
        $response['errors'] = $errors;
    }
}


$conn->close();


echo json_encode($response);
exit();
