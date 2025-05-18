<?php
include '../../connect.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid Request.'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['customer_id'])) {
    $customerId = filter_var($_POST['customer_id'], FILTER_VALIDATE_INT);

    if ($customerId === false || $customerId <= 0) {
        $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Invalid Customer ID.</p>';
    } else {

        $newStatus = 'deleted';
        $sql = "UPDATE users SET status = ? WHERE id = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("si", $newStatus, $customerId);

            if ($stmt->execute()) {

                if ($stmt->affected_rows > 0) {
                    $response['status'] = '<p><i class="fa-regular fa-circle-check green icon"></i>success</p>';
                    $response['message'] = '<p><i class="fa-regular fa-circle-check green icon"></i>Customer marked as deleted successfully.</p>';
                } else {

                    $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Customer not found or status already set.</p>';
                }
            } else {
                $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Error executing update statement: </p>' . $stmt->error;
                $response['sql_error'] = $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Error preparing update statement: </p>' . $conn->error;
            $response['sql_error'] = $conn->error;
        }
    }
} else {
    $response['message'] = '<p><i class="fa-regular fa-circle-xmark red icon"></i>Invalid request method or missing customer ID.</p>';
}

$conn->close();

echo json_encode($response);
exit();
