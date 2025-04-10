<?php
    require_once 'connect.php';
    require_once 'getUser.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        $user=getUser($username);
        if ($user!=null) 
        {
            if (password_verify($password,$user['password'])) 
            {
                require 'getPermission.php';
                require 'responseHTML.php';
                session_start();
                $_SESSION['user']=$user;
                $perms=getPermissions($user['username']);

                $response = [
                    'status' => 'success',
                    'message' => '<p><i class="fa-regular fa-circle-check green icon"></i>Đăng nhập thành công!</p>',
                    'html' => responseHTML($perms,$user),
                    'default' => !empty($perms)?$perms[0]:'',
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Tên đăng nhập hoặc mật khẩu không đúng!</p>',
                    'html' => '',
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => '<p><i class="fa-regular fa-circle-xmark red icon"></i>Tên đăng nhập hoặc mật khẩu không đúng!</p>',
                'html' => '',
            ];
        }

        //thong bao tra ve chuoi json cho client
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode([
            'status' => 'error',
            'message' => 'Phương thức yêu cầu không hợp lệ'
        ]);
        exit();
    }

    $conn->close();
?>
