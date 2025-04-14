<?php
$password = "123456";

// Hash mật khẩu với thuật toán bcrypt
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// In ra giá trị đã hash
echo $hashedPassword;
?>