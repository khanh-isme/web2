<?php
    require_once('config.php');
    function get_product_sizes($product_id) {
    global $connection;

    if (!is_numeric($product_id) || $product_id <= 0) {
        return [];
    }

    $sql = "SELECT id,size, stock FROM product_size WHERE product_id = ?";
    $stmt = $connection->prepare($sql);

    if (!$stmt) return [];

    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sizes = [];
    while ($row = $result->fetch_assoc()) {
        $sizes[] = $row; // ['size' => ..., 'stock' => ...]
    }

    $stmt->close();
    return $sizes;
}
?>