<?php
require_once '../../connect.php';

$type = $_POST['type'] ?? '';

function renderTable($headers, $rows)
{
    $html = "<thead><tr>";
    $html .= "<th>STT</th>";
    foreach ($headers as $header) {
        $html .= "<th>$header</th>";
    }
    $html .= "</tr></thead><tbody>";

    $stt = 1;
    foreach ($rows as $row) {
        $html .= "<tr>";
        $html .= "<td>$stt</td>";
        foreach ($row as $cell) {
            $html .= "<td>$cell</td>";
        }
        $html .= "</tr>";
        $stt++;
    }

    $html .= "</tbody>";
    return $html;
}

switch ($type) {
    case 'Sản phẩm bán chạy trong tuần':
        $stmt = $conn->prepare("
            SELECT p.name, c.name AS category, SUM(od.quantity) AS total_sold
            FROM orders o
            JOIN order_details od ON o.order_id = od.order_id
            JOIN product_size ps ON od.product_size_id = ps.id
            JOIN products p ON ps.product_id = p.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE o.status='delivered' and WEEK(o.order_date, 1) = WEEK(CURDATE(), 1)
            GROUP BY ps.product_id
            ORDER BY total_sold DESC
        ");
        $stmt->execute();
        $stmt->bind_result($productName, $categoryName, $totalSold);
        $rows = [];
        while ($stmt->fetch()) {
            $rows[] = [$productName, $categoryName ?? 'Chưa phân loại', $totalSold];
        }
        echo renderTable(['Tên sản phẩm', 'Loại', 'Số lượng bán'], $rows);
        break;

    case 'Sản phẩm bán chạy trong tháng':
        $stmt = $conn->prepare("
            SELECT p.name AS product_name, c.name AS category_name, SUM(od.quantity) AS total_sold
            FROM order_details od
            JOIN product_size ps ON od.product_size_id = ps.id
            JOIN products p ON ps.product_id = p.id
            LEFT JOIN categories c ON p.category_id = c.id
            JOIN orders o ON od.order_id = o.order_id
            WHERE o.status='delivered' and MONTH(o.order_date) = MONTH(CURDATE()) AND YEAR(o.order_date) = YEAR(CURDATE())
            GROUP BY ps.product_id
            ORDER BY total_sold DESC
        ");
        $stmt->execute();
        $stmt->bind_result($productName, $categoryName, $totalSold);
        $rows = [];
        while ($stmt->fetch()) {
            $rows[] = [$productName, $categoryName ?? 'Chưa phân loại', $totalSold];
        }
        echo renderTable(['Tên sản phẩm', 'Loại', 'Số lượng bán'], $rows);
        break;

    case 'Sản phẩm có doanh thu cao nhất trong tuần':
        $stmt = $conn->prepare("
                SELECT p.name, c.name AS category, SUM(od.price) AS revenue
                FROM orders o
                JOIN order_details od ON o.order_id = od.order_id
                JOIN product_size ps ON od.product_size_id = ps.id
                JOIN products p ON ps.product_id = p.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE o.status='delivered' and  WEEK(o.order_date, 1) = WEEK(CURDATE(), 1)
                GROUP BY ps.product_id
                ORDER BY revenue DESC
            ");
        $stmt->execute();
        $stmt->bind_result($productName, $categoryName, $revenue);
        $rows = [];
        while ($stmt->fetch()) {
            $rows[] = [
                $productName,
                $categoryName ?? 'Chưa phân loại',
                number_format($revenue) . "đ"
            ];
        }
        echo renderTable(['Tên sản phẩm', 'Loại', 'Doanh thu'], $rows);
        break;

    case 'Sản phẩm có doanh thu cao nhất trong tháng':
        $stmt = $conn->prepare("
                SELECT p.name, c.name AS category, SUM(od.price) AS revenue
                FROM orders o
                JOIN order_details od ON o.order_id = od.order_id
                JOIN product_size ps ON od.product_size_id = ps.id
                JOIN products p ON ps.product_id = p.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE o.status='delivered' and  MONTH(o.order_date) = MONTH(CURDATE()) AND YEAR(o.order_date) = YEAR(CURDATE())
                GROUP BY ps.product_id
                ORDER BY revenue DESC
            ");
        $stmt->execute();
        $stmt->bind_result($productName, $categoryName, $revenue);
        $rows = [];
        while ($stmt->fetch()) {
            $rows[] = [
                $productName,
                $categoryName ?? 'Chưa phân loại',
                number_format($revenue) . "đ"
            ];
        }
        echo renderTable(['Tên sản phẩm', 'Loại', 'Doanh thu'], $rows);
        break;

    case 'Khách hàng có doanh thu cao nhất':
        $stmt = $conn->prepare("
                SELECT u.name, u.email, u.phone, u.address, SUM(o.total_amount) AS revenue
                FROM orders o
                JOIN users u ON o.customer_id = u.id
                where o.status='delivered'
                GROUP BY o.customer_id
                ORDER BY revenue DESC
            ");
        $stmt->execute();
        $stmt->bind_result($name, $email, $phone, $address, $revenue);
        $rows = [];
        while ($stmt->fetch()) {
            $rows[] = [
                $name,
                $email ?? 'Chưa có email',
                $phone ?? 'Chưa có SĐT',
                $address ?? 'Chưa có địa chỉ',
                number_format($revenue) . "đ"
            ];
        }
        echo renderTable(['Tên khách hàng', 'Email', 'SĐT', 'Địa chỉ', 'Tổng doanh thu'], $rows);
        break;

    case 'Khách hàng tiềm năng':
        $stmt = $conn->prepare("
                SELECT u.name, u.email, u.phone, u.address, COUNT(o.order_id) AS num_orders
                FROM orders o
                JOIN users u ON o.customer_id = u.id
                WHERE o.status='delivered' and o.order_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY o.customer_id
                ORDER BY num_orders DESC
            ");
        $stmt->execute();
        $stmt->bind_result($name, $email, $phone, $address, $count);
        $rows = [];
        while ($stmt->fetch()) {
            $rows[] = [
                $name,
                $email ?? 'Chưa có email',
                $phone ?? 'Chưa có SĐT',
                $address ?? 'Chưa có địa chỉ',
                $count . ' đơn hàng'
            ];
        }
        echo renderTable(['Tên khách hàng', 'Email', 'SĐT', 'Địa chỉ', 'Số đơn hàng'], $rows);
        break;

    case 'Khách hàng trung thành':
        $stmt = $conn->prepare("
                SELECT 
                    u.name,
                    u.email,
                    u.phone,
                    u.address,
                    COUNT(o.order_id) AS total_orders,
                    SUM(o.total_amount) AS total_spent,
                    COUNT(DISTINCT DATE_FORMAT(o.order_date, '%Y-%m')) AS active_months,
                    TIMESTAMPDIFF(MONTH, MIN(o.order_date), CURDATE()) AS months_since_first_order,
                    (
                        COUNT(o.order_id) * 2 +
                        FLOOR(SUM(o.total_amount) / 100000) +
                        COUNT(DISTINCT DATE_FORMAT(o.order_date, '%Y-%m')) * 5 +
                        TIMESTAMPDIFF(MONTH, MIN(o.order_date), CURDATE()) * 1
                    ) AS loyalty_score
                FROM users u
                JOIN orders o ON u.id = o.customer_id
                where o.status='delivered'
                GROUP BY u.id
                ORDER BY loyalty_score DESC
            ");
        $stmt->execute();
        $stmt->bind_result($name, $email, $phone, $address, $totalOrders, $totalSpent, $activeMonths, $monthsSinceFirst, $loyaltyScore);
        $rows = [];
        while ($stmt->fetch()) {
            $rows[] = [
                $name,
                $email ?? 'Chưa có email',
                $phone ?? 'Chưa có SĐT',
                $address ?? 'Chưa có địa chỉ',
                $totalOrders,
                number_format($totalSpent) . 'đ',
                $activeMonths . ' tháng',
                $monthsSinceFirst . ' tháng',
                $loyaltyScore . ' điểm'
            ];
        }
        echo renderTable([
            'Tên KH',
            'Email',
            'SĐT',
            'Địa chỉ',
            'Số đơn',
            'Tổng chi',
            'Số tháng hoạt động',
            'Thời gian kể từ đơn đầu',
            'Điểm trung thành'
        ], $rows);
        break;

    default:
        echo "<tr><td colspan='3'>Không xác định yêu cầu</td></tr>";
        break;
}
