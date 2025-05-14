<?php
require_once("../../connect.php");

function getWeeklyValue($conn, $query) {
    $value = 0;
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($value);
        $stmt->fetch();
        $stmt->close();
    }
    return $value;
}

function percentChange($current, $previous) {
    if ($previous == 0) return $current == 0 ? 0 : 100;
    return round((($current - $previous) / $previous) * 100, 2);
}

function formatChangeHTML($change) {
    $color = $change >= 0 ? "green" : "red";
    $icon = $change >= 0 ? "Tăng" : "Giảm";
    return "<span style='color: $color;font-size:12px'>$icon $change% so với tuần trước</span>";
}

// Dữ liệu tuần này
$weeklyIncomes = getWeeklyValue($conn, "
    SELECT SUM(total_amount)
    FROM orders
    WHERE WEEK(order_date, 1) = WEEK(NOW(), 1) AND YEAR(order_date) = YEAR(NOW())
");
$weeklyOrders = getWeeklyValue($conn, "
    SELECT COUNT(*)
    FROM orders
    WHERE WEEK(order_date, 1) = WEEK(NOW(), 1) AND YEAR(order_date) = YEAR(NOW())
");
$addedToCart = getWeeklyValue($conn, "
    SELECT COUNT(*)
    FROM cart
    WHERE WEEK(added_at, 1) = WEEK(NOW(), 1) AND YEAR(added_at) = YEAR(NOW())
");

// Dữ liệu tuần trước
$lastWeeklyIncomes = getWeeklyValue($conn, "
    SELECT SUM(total_amount)
    FROM orders
    WHERE WEEK(order_date, 1) = WEEK(NOW(), 1) - 1 AND YEAR(order_date) = YEAR(NOW())
");
$lastWeeklyOrders = getWeeklyValue($conn, "
    SELECT COUNT(*)
    FROM orders
    WHERE WEEK(order_date, 1) = WEEK(NOW(), 1) - 1 AND YEAR(order_date) = YEAR(NOW())
");
$lastAddedToCart = getWeeklyValue($conn, "
    SELECT COUNT(*)
    FROM cart
    WHERE WEEK(added_at, 1) = WEEK(NOW(), 1) - 1 AND YEAR(added_at) = YEAR(NOW())
");

// HTML hiển thị
echo json_encode([
    "status" => "success",
    "weeklyIncomes" => "<h3>Doanh thu</h3>" . "<p style='line-height: 40px; font-size: 20px'>" . number_format((float)$weeklyIncomes, 0, '.', ',') . "đ</p>" . formatChangeHTML(percentChange($weeklyIncomes, $lastWeeklyIncomes)),
    "weeklyOrders" => "<h3>Đơn hàng</h3>" . "<p style='line-height: 40px; font-size: 20px'>" . $weeklyOrders . "</p>" . formatChangeHTML(percentChange($weeklyOrders, $lastWeeklyOrders)),
    "addedToCart" => "<h3>Giỏ hàng</h3>" . "<p style='line-height: 40px; font-size: 20px'>" . $addedToCart . "</p>" . formatChangeHTML(percentChange($addedToCart, $lastAddedToCart))
]);
?>
