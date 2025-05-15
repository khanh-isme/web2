<?php
require_once '../../connect.php';
require_once 'Category.php';

header('Content-Type: application/json');

$categoryObj = new Category($conn);

$categories = $categoryObj->getAllCategories();
$collections = $collectionObj->getAllCollections();

$query = "SELECT DISTINCT gender FROM products";
$result = $conn->query($query);
$genders = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $genders[] = $row['gender'];
    }
}

echo json_encode([
    'gender' => $genders,
    'categories' => $categories,
]);

$conn->close();
