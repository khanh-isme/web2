<div id="right_content">
    <div class="STATS content-ctn">
    STATS
    </div>
    <div class="PRODUCTS content-ctn">
    PRODUCTS
    </div>
    <div class="ORDERS content-ctn">
    ORDERS
    </div>
    <div class="CUSTOMERS content-ctn">
    <style>
        <?php include('css/customer.css'); ?>
    </style>

<div class="account-management-container"> <header class="account-management-header">
        <h1>Quản lý tài khoản</h1>
    </header>

    <div class="tab-container">
        <div class="tab active" data-tab="profile">Thông tin tài khoản</div>
        <div class="tab" data-tab="password">Đổi mật khẩu</div>
        <div class="tab" data-tab="orders">Đơn hàng của tôi</div>
    </div>

    <div id="profile" class="tab-content active"> <h2>Thông tin tài khoản</h2>
        <form method="post" action="#"> <div class="form-group">
                <label for="name">Họ tên:</label>
                <input type="text" id="name" name="name" value="" placeholder="Nhập họ tên của bạn" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="" placeholder="Nhập địa chỉ email" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="tel" id="phone" name="phone" value="" placeholder="Nhập số điện thoại">
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <textarea id="address" name="address" rows="3" placeholder="Nhập địa chỉ của bạn"></textarea>
            </div>

            <button type="submit">Cập nhật thông tin</button> </form>
    </div>

    <div id="password" class="tab-content" > <h2>Đổi mật khẩu</h2>
        <form method="post" action="#"> <div class="form-group">
                <label for="current_password">Mật khẩu hiện tại:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">Mật khẩu mới:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit">Đổi mật khẩu</button> </form>
    </div>

    <div id="orders" class="tab-content"> <h2>Đơn hàng của tôi</h2>

        <table>
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="Mã đơn hàng">#12345</td>
                    <td data-label="Ngày đặt">15/04/2025 10:30</td>
                    <td data-label="Tổng tiền">1.250.000đ</td>
                    <td data-label="Trạng thái">
                        <span class="order-status status-delivered">
                            Đã giao hàng
                        </span>
                    </td>
                    <td data-label="Thao tác">
                        <a href="#view-order-12345" class="btn btn-secondary">Xem chi tiết</a>
                        </td>
                </tr>
                 <tr>
                    <td data-label="Mã đơn hàng">#67890</td>
                    <td data-label="Ngày đặt">16/04/2025 08:15</td>
                    <td data-label="Tổng tiền">780.000đ</td>
                    <td data-label="Trạng thái">
                        <span class="order-status status-processing">
                           Đang xử lý
                        </span>
                    </td>
                    <td data-label="Thao tác">
                         <a href="#view-order-67890" class="btn btn-secondary">Xem chi tiết</a>
                         <button class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">Hủy đơn</button>
                    </td>
                </tr>
                 <tr>
                    <td data-label="Mã đơn hàng">#11223</td>
                    <td data-label="Ngày đặt">10/04/2025 14:00</td>
                    <td data-label="Tổng tiền">320.000đ</td>
                    <td data-label="Trạng thái">
                        <span class="order-status status-canceled">
                           Đã hủy
                        </span>
                    </td>
                    <td data-label="Thao tác">
                         <a href="#view-order-11223" class="btn btn-secondary">Xem chi tiết</a>
                    </td>
                </tr>
            </tbody>
        </table>

        </div> <div id="view-order-12345" class="order-details" >
         <h3>Chi tiết đơn hàng #12345</h3>
         <p><strong>Ngày đặt hàng:</strong> 15/04/2025 10:30</p>
         <p><strong>Trạng thái:</strong>
             <span class="order-status status-delivered">
                 Đã giao hàng
             </span>
         </p>
         <p><strong>Địa chỉ giao hàng:</strong> 123 Đường ABC, Phường X, Quận Y, TP. HCM</p>

         <h4 style="margin-top: 20px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">Sản phẩm trong đơn hàng:</h4>

         <div class="order-item">
             <div class="order-item-image">
                 <img src="https://via.placeholder.com/80" alt="Sản phẩm A">
             </div>
             <div class="order-item-details">
                 <h4>Tên Sản phẩm A rất dài để kiểm tra xuống dòng</h4>
                 <p>Giá: 500.000đ</p>
                 <p>Số lượng: 2</p>
                 <p>Thành tiền: 1.000.000đ</p>
             </div>
         </div>

         <div class="order-item">
             <div class="order-item-image">
                  <img src="https://via.placeholder.com/80" alt="Sản phẩm B">
             </div>
             <div class="order-item-details">
                 <h4>Sản phẩm B</h4>
                 <p>Giá: 250.000đ</p>
                 <p>Số lượng: 1</p>
                 <p>Thành tiền: 250.000đ</p>
             </div>
         </div>

         <div class="order-total">
             Tổng thanh toán: 1.250.000đ
         </div>

         <a href="#" class="back-link" onclick="/* Thêm JS để ẩn div này và quay lại tab orders */">
             <i class="fas fa-arrow-left"></i> Quay lại danh sách đơn hàng
         </a>
     </div>


</div>
<script>
    
</script>
    </div>
    <div class="EMPLOYEES content-ctn">
    EMPLOYEES
    </div>
    <div class="SUPPLIERS content-ctn">
    SUPPLIERS
    </div>
</div>