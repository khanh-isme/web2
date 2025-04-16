<html>
    <head>
        <title>Shoes Capital Crew Admin</title>
        <meta charset="UTF-8">
        <link rel="icon" href="imgs/favicon.png">
        <link rel="stylesheet" href="css/admin_login.css">
        <link rel="stylesheet" href="css/left_menu.css">
        <link rel="stylesheet" href="css/right_content.css">
        <link rel="stylesheet" href="css/admin.css">
        <link rel="stylesheet" href="css/customer.css">
        <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>

    <body>
        <?php require_once 'includes/connect.php';?>
        <div id="messageDialog">
            
        </div>
        <div id="body">
            
        <div>
        <?php require "includes/admin_right_content.php";?>
    </body>
    <script src="js/admin_messageDialog.js"></script>
    <script src="js/admin_login.js"></script>
    <script src="js/admin_logout.js"></script>
    <script src="js/admin.js"></script>
    <script src="js/left_menu.js"></script>
</html>