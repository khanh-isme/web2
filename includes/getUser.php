<?php
    require_once('config.php');
    function getUser($username)
    {
        global $connection;
        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username); // s la du lieu kieu chuoi
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows>0)
        {
            return $result->fetch_assoc();
        }
        else
        {
            return null;
        }
    }
?>