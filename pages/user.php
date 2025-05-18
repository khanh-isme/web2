
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nike</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    

    <!-- CSS -->
    <link rel="stylesheet" href="/web2/assets/css/user.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
     
</head>
<body>
    
    <div class="user-info-container">
  <h2>Thông tin người dùng</h2>

  <div id="user-info">
    <p><strong>Họ tên:</strong> <span id="name"></span></p>
    <p><strong>Email:</strong> <span id="email"></span></p>
    <p><strong>Số điện thoại:</strong> <span id="phone"></span></p>
    <p><strong>Địa chỉ:</strong> <span id="address"></span></p>
    <p><strong>Trạng thái:</strong> <span id="status"></span></p>
  </div>

    <!-- Nút chỉnh sửa -->
    <button id="edit-user-btn">Chỉnh sửa thông tin</button>

    
    <!-- Nút đăng xuất -->
    <button id="logout-btn">Đăng xuất</button>

  <div id="user-error" class="error-message" style="color:red;"></div>
  </div>



    <div id="order-list">
    <!-- Danh sách đơn hàng sẽ được hiển thị ở đây -->
    </div>

    <!-- HTML -->
     <div class="order-details-dialog">

        <!-- render -->
       <div id="order-dialog" ></div>

        <div class="exit-icon" id="closeDetails" onclick="setupCloseOrderDetails()">
            <i class='bx bx-x'></i>
        </div>
       
     </div>


  <!-- Overlay nền mờ -->
<div id="edit-user-overlay" class="overlay" style="display: none;"></div>

<!-- Popup chỉnh sửa -->
<div id="edit-user-popup" class="popup" style="display: none;">
  <h3>Chỉnh sửa thông tin</h3>
  <form id="edit-user-form">
    <label>Họ tên: <input type="text" id="edit-name" required></label><br>
    <label>Email: <input type="email" id="edit-email" required></label><br>
    <label>SĐT: <input type="text" id="edit-phone" required></label><br>
    <label>Địa chỉ: <input type="text" id="edit-address" required></label><br><br>
    <button type="submit">💾 Lưu</button>
    <button type="button" id="cancel-edit">❌ Hủy</button>
  </form>
</div>





     

    
</body>