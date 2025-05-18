<?php
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user'])) {
    echo "<p>Bạn chưa đăng nhập.</p>";
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Information</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="/web2/assets/css/ship.css">
</head>
<body>
<div class="container">
    <div class="form-section">
        <div class="button-group">
            <button class="button-primary">Shipping Detail</button>
        </div>

        <div class="input-group">
            <label for="name">Name</label>
            <input type="text" id="name" value="<?= htmlspecialchars($user['name']) ?>" >
        </div>

        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" >
        </div>

        <div class="input-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" value="<?= htmlspecialchars($user['phone']) ?>">
        </div>

        <div class="input-group">
            <label for="address">Shipping Address</label>
            <input type="text" id="address" value="<?= htmlspecialchars($user['address']) ?>">
        </div>

        <div class="input-checkbox">
            <input type="checkbox" id="useLatestShipping">
            <label for="useLatestShipping">Dùng địa chỉ giao hàng gần nhất</label>
        </div>

        <div class="paym">
            <h3>Payment</h3>
        </div>
        <div class="payment-methods">
            <label>
                <input type="radio" name="paymentMethod" value="cod" checked> Tiền mặt khi nhận hàng
            </label><br>
            <label>
                <input type="radio" name="paymentMethod" value="online"> Thanh toán trực tuyến
            </label>
        </div>

        <div class="input-group online-payment-fields" style="display:none;">
            <label for="cardName">Name on card</label>
            <input type="text" id="cardName">
        </div>
        <div class="input-group online-payment-fields" style="display:none;">
            <label for="cardNumber">Card number</label>
            <input type="text" id="cardNumber">
        </div>
        <div class="input-group online-payment-fields" style="display:none;">
            <label for="expiryDate">Expiry Date (MM/YY)</label>
            <input type="text" id="expiryDate">
        </div>
        <div class="input-group online-payment-fields" style="display:none;">
            <label for="cvv">CVV</label>
            <input type="text" id="cvv">
        </div>

        <button class="place-order">Place Order</button>
    </div>

    <div class="summary-section">
        <h2>Order Summary</h2>
        <div class="order-summary" id="orderSummary">
            <!-- Order details will be inserted via JavaScript -->
        </div>
    </div>
</div>

<!-- JS -->

</body>
</html>
