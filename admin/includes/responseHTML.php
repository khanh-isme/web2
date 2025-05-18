<?php
function responseHTML($permissions, $user)
{
    $permissionsMap = [];
    foreach ($permissions as $perm) {
        $permissionsMap[$perm] = true;
    };

    $html = "";
    $right = '<div id="right_content">';
    $html .= "<div id='left_menu'>
    <div id='logo-ctn'>
        <img src='imgs/logo.png' id='logo'>
    </div>
    <div id='menu-ctn'>
        <ul id='menu'>";
    if (isset($permissionsMap["STATS"])) {
        $html .= "<li class='menu-item STATS' data-value='STATS'>
                    <lb><i class='fa-solid fa-chart-column menu-icon'></i>Thống kê doanh thu</lb>
                </li>";

        $right .= '<div class="STATS content-ctn">
                <div id="stats-ctn">
                    <div id="chart-ctn" class="hidden">
                        <canvas id="status-chart" width="300" height="150"></canvas>
                        <div id="chart-input-warning" class=""><i class="fa-solid fa-triangle-exclamation"></i>Không có dữ liệu!</div>
                        <div id="chart-footer">
                            <input type="button" id="chart-reload" value="Làm mới">
                        </div>
                    </div>
                    <div id="stats">
                        <div id="chart-mode-ctn"><input type="button" id="chart-mode" value="Xem biểu đồ"></div>
                        <h3>Thống kê trong tuần</h3>
                        <div id="weekly-stats-ctn">
                            <div id="weekly-incomes"></div>
                            <div id="weekly-orders"></div>
                            <div id="added-to-cart"></div>
                        </div>
                        <div id="ranking-ctn">
                            <select name="ranking-select" id="ranking-select">
                                <option>Sản phẩm bán chạy trong tuần</option>
                                <option>Sản phẩm bán chạy trong tháng</option>
                                <option>Sản phẩm có doanh thu cao nhất trong tuần</option>
                                <option>Sản phẩm có doanh thu cao nhất trong tháng</option>
                                <option>Khách hàng có doanh thu cao nhất</option>
                                <option>Khách hàng tiềm năng</option>
                                <option>Khách hàng trung thành</option>
                            </select>
                            <div id="ranking-time">
                                <div class="ranking-time-input">
                                    <label>Từ ngày: </label>
                                    <input type="date" id="ranking-time-from" value="2023-01-01">
                                </div>
                                <div class="ranking-time-input">
                                    <label>Đến ngày: </label>
                                    <input type="date" id="ranking-time-to" value="'.date('Y-m-d').'">
                                </div>
                            </div>
                            <table id="ranking-table">
                                
                            </table>
                            <div id="loyalty-explanation" style="padding: 10px; background-color: whitesmoke; border-left: 4px solid gray;">
                                <h4 style="margin-top: 0;">Cách tính điểm trung thành</h4>
                                <ul style="margin: 0; padding-left: 20px;">
                                    <li>Mỗi đơn hàng: <strong>+2 điểm</strong></li>
                                    <li>Mỗi 100.000đ chi tiêu: <strong>+1 điểm</strong></li>
                                    <li>Mỗi tháng khách hàng có mua hàng: <strong>+5 điểm</strong></li>
                                    <li>Mỗi tháng tính từ đơn đầu tiên đến hiện tại: <strong>+1 điểm</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> 
                <form id="stats-menu-option-form" class="hidden">
                    <div class="stats-option-ctn">
                        <label for="stats-by-slt">Thống kê theo:</label>
                        <select name="stats-by-slt" id="stats-by-slt" class="stats-option-slt">
                            <option value="total-category">Tổng - Loại</option>
                            <option value="total-time">Tổng - Mốc thời gian</option>
                            <option value="category-time">Loại - Mốc thời gian</option>
                        </select>
                        <br>
                        <div id="stats-by-options" class="stats-option-ctn border">
                            <label for="stats-by-time-option">Tùy chọn:</label>
                            <select name="stats-by-time-option" id="stats-by-time-option" class="stats-option-slt">
                                <option value="all">Tất cả</option>
                                <option value="time">Theo thời gian</option>
                            </select>
                            <div class="stats-option-ctn disabled" id="stats-time">
                                <label>Thời gian:</label>
                                <div id="time-range-option-ctn" class="stats-option-ctn border">
                                    <div id="by-day-option-ctn" class="stats-option">
                                        <label for="by-day-from">Từ ngày:</label>
                                        <input name="by-day-from" id="by-day-from" type="date" value="' . date("Y-m-d") . '" class="stats-option-slt">
                                        <label for="by-day-to">Đến hết ngày:</label>
                                        <input name="by-day-to" id="by-day-to" type="date" value="' . date("Y-m-d") . '" class="stats-option-slt">
                                    </div>
                                </div>
                            </div>
                            <div id="time-range-unit-ctn" class="disabled">
                                <label for="time-range-unit">Đơn vị thời gian:</label>
                                <select name="time-range-unit" id="time-range-unit" class="stats-option-slt">
                                    <option value="day">Ngày</option>
                                    <option value="month">Tháng</option>
                                    <option value="year">Năm</option>
                                </select>
                            </div>
                            <div id="category-menu-ctn">
                                <label>Chọn loại:</label>
                                <div id="category-menu" class="stats-option-ctn border">
                                    <label><input class="category-cb" type="checkbox" name="category[]" value="Sneakers" checked>Sneakers</label>
                                    <label><input class="category-cb" type="checkbox" name="category[]" value="Boots" checked>Boots</label>
                                    <label><input class="category-cb" type="checkbox" name="category[]" value="Sandals" checked>Sandals</label>
                                    <label><input class="category-cb" type="checkbox" name="category[]" value="Loafers" checked>Loafers</label>
                                    <label><input class="category-cb" type="checkbox" name="category[]" value="Athletic" checked>Athletic</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>';
    }
    if (isset($permissionsMap["PRODUCTS"])) {
        $html .= "<li class='menu-item PRODUCTS' data-value='PRODUCTS'>
                    <lb><i class='fa-solid fa-cube menu-icon'></i>Sản phẩm</lb>
                </li>";

        $right .= '<div class="PRODUCTS content-ctn">
        <div class="product-content">
            <div class="product-header">
                <h1>Products</h1>
        ';
        if (isset($permissionsMap["ADD_PRODUCT"]))
            $right .= '<div class="product-header-buttons">
                    <button class="product-add-button">ADD PRODUCT</button>
                </div>';

        $right .= '</div>

            <form id="product-filter-form" method="GET" action="productFilter.php">
                <div class="product-search-section">
                    <div class="product-search-title">SEARCH FOR PRODUCT</div>
                    <div class="product-search-inputs">
                        <div class="product-search-row">
                            <input type="text" name="keyword" placeholder="Enter name, SKU, or supplier code" class="product-search-bar">
                        </div>
                        <div class="product-search-row">
                            <!-- CATEGORY -->
                            <div class="product-filter-group">
                                <label for="product-filter-category">CATEGORY</label>
                                <select id="product-filter-category" name="category_id">
                                    <!-- Options sẽ được tải từ database -->
                                </select>
                            </div>

                            <!-- GENDER -->
                            <div class="product-filter-group">
                                <label for="product-filter-gender">GENDER</label>
                                <select id="product-filter-gender" name="gender">
                                    <option value="">All</option>
                                    <option value="nam">Nam</option>
                                    <option value="nu">Nữ</option>
                                    <option value="unisex">Unisex</option>
                                </select>
                            </div>

                            <!-- PRICE RANGE -->
                            <div class="product-filter-group">
                                <label for="product-filter-price_min">PRICE</label>
                                <div style="display: flex; gap: 5px;">
                                    <input type="number" name="price_min" id="product-filter-price_min" placeholder="Min" style="width: 70px;">
                                    <span>-</span>
                                    <input type="number" name="price_max" id="product-filter-price_max" placeholder="Max" style="width: 70px;">
                                </div>
                            </div>

                            <!-- SEARCH BUTTON -->
                            <div class="product-filter-group" style="align-self: end;">
                                <button type="submit" class="product-search-button">SEARCH</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <p class="product-status-info">SHOWING IS ACTIVE PRODUCTS</p>

            <div class="table-scroll">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>IMAGE</th>
                            <th>NAME</th>
                            <th>CATEGORY</th>
                            <th>INVENTORY</th>
                            <th>RETAIL PRICE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- Thêm các hàng khác -->
                    </tbody>
                </table>
            </div>
        </div>';
        if (isset($permissionsMap["EDIT_PRODUCT"]))
            $right .= '
        <!-- Modal Form for Editing Product -->
        <form id="product-edit-form" action="includes/updateProduct.php" method="POST" enctype="multipart/form-data">
            <div id="product-edit-modal" class="product-modal">
                <div class="product-modal-content">
                    <div class="product-modal-header">
                        <h1>Edit Product</h1>
                        <span class="product-close-button">&times;</span>
                    </div>
                    <div class="product-modal-body">
                        <div class="product-modal-left">
                            <img id="product-edit-image" alt="Product Image" class="product-image-preview">
                            <label for="product-change-image" class="custom-file-label">Change the Image</label>
                            <input type="file" name="product_image" id="product-change-image" class="change-image-button" accept="image/*">
                        </div>
                        <div class="product-modal-right">
                            <div class="product-form-group">
                                <label for="product-edit-id">Product ID</label>
                                <input type="text" name="id" id="product-edit-id" readonly>
                            </div>
                            <div class="product-form-group">
                                <label for="product-edit-name">Name</label>
                                <input type="text" name="name" id="product-edit-name">
                            </div>
                            <div class="product-form-group">
                                <label for="product-edit-price">Price</label>
                                <input type="number" name="price" id="product-edit-price" step="any">
                            </div>
                            <div class="product-form-group">
                                <label for="product-edit-category">Category</label>
                                <select name="category_id" id="product-edit-category"></select>
                            </div>
                            <div class="product-form-group">
                                <label for="product-edit-gender">Gender</label>
                                <select id="product-edit-gender" name="gender">
                                    <option value="nam">Nam</option>
                                    <option value="nu">Nữ</option>
                                    <option value="unisex">Unisex</option>
                                </select>
                            </div>
                            <div class="product-form-group">
                                <label for="product-edit-stock">Stock</label>
                                <textarea id="product-edit-stock" name="product_stock" readonly></textarea>                            </div>
                            <div class="product-form-group">
                                <label for="product-edit-details">Details</label>
                                <textarea name="description" id="product-edit-details"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="product-modal-footer">
                        <button type="submit" class="product-edit-save-button">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>';
        if (isset($permissionsMap['ADD_PRODUCT']))
            $right .= '
        <!-- ADD Product Modal -->
        
        <form id="product-add-form" action="includes/addProduct.php" method="POST" enctype="multipart/form-data">
            <!-- Modal Form for Adding Product -->
            <div id="product-add-modal" class="product-add-modal">
                <div class="product-add-modal-content">
                    <!-- Header -->
                    <div class="product-add-modal-header">
                        <h1>Add Product</h1>
                        <span class="product-add-close-button" id="add-modal-close">×</span>
                    </div>
                    <!-- Body -->
                    <div class="product-add-modal-body">
                        <!-- Cột 1: Thêm ảnh -->
                        <div class="product-add-modal-column product-add-modal-left">
                            <div class="product-image-upload">
                            <img id="add-product-image-preview" src="#" alt="Image Preview" class="product-image-preview" style="display: none;">
                                <label for="add-product-image">Upload Image</label>
                                <input type="file" id="add-product-image" name="product_image" accept="image/*">
                            </div>
                        </div>
                        <!-- Cột 2: Nhập thông tin sản phẩm -->
                        <div class="product-add-modal-column product-add-modal-center">
                            <div class="product-form-group">
                                <label for="add-product-name">Name</label>
                                <input type="text" id="add-product-name" name="product_name" placeholder="Enter product name">
                            </div>
                            <div class="product-form-group">
                                <label for="add-product-category">Category</label>
                                <select id="add-product-category" name="product_category">
                                    <!-- Options sẽ được tải từ database -->
                                </select>
                            </div>
                            <div class="product-form-group">
                                <label for="add-product-gender">Gender</label>
                                <select id="add-product-gender" name="gender">
                                    <option value="nam">Nam</option>
                                    <option value="nu">Nữ</option>
                                    <option value="unisex">Unisex</option>
                                </select>
                            </div>
                            <div class="product-form-group">
                                <label for="add-product-description">Description</label>
                                <textarea id="add-product-description" name="product_description" placeholder="Enter product description"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="product-add-modal-footer">
                        <button type="submit" class="product-add-save-button" id="add-product-save">Save Product</button>
                    </div>
                </div>
            </div>
        </form>';
        $right .= '
    </div>';
    }
    if (isset($permissionsMap["ORDERS"])) {
        $html .= "<li class='menu-item ORDERS' data-value='ORDERS'>
                    <lb><i class='fa-solid fa-receipt menu-icon'></i>Đơn hàng</lb>
                </li>";

        $right .= '<div class="ORDERS content-ctn">
                <div class="order-content"> <div class="order-header">
                        <h1>Orders</h1>
                        </div>

                    <form method="POST" action="#" id="order-search-form">
                        <div class="order-search-section"> <div class="order-search-title">SEARCH & FILTER ORDERS</div> <div class="order-search-inputs"> <div class="order-search-row"> <div class="order-filter-group"> <label for="search_order_id">Order ID</label>
                                        <input type="number" name="search_order_id" id="search_order_id" placeholder="Enter Order ID..." class="order-search-bar"> </div>
                                    <div class="order-filter-group"> <label for="search_customer_id">Customer ID</label>
                                        <input type="number" name="search_customer_id" id="search_customer_id" placeholder="Enter Customer ID..." class="order-search-bar"> </div>
                                    <div class="order-filter-group"> <label for="search_status">Status</label>
                                        <select name="search_status" id="search_status" class="order-search-bar">
                                            <option value="pending">Pending</option>
                                            <option value="processing">Processing</option>
                                            <option value="shipped">Shipped</option>
                                            <option value="delivered">Delivered</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="order-search-row"> <div class="order-filter-group"> <label for="search_order_date_from">Order Date From</label>
                                        <input type="date" name="search_order_date_from" id="search_order_date_from" class="order-search-bar"> </div>
                                    <div class="order-filter-group"> <label for="search_order_date_to">Order Date To</label>
                                        <input type="date" name="search_order_date_to" id="search_order_date_to" class="order-search-bar"> </div>
                                </div>
                                <div class="order-search-row"> <div class="order-filter-group"> <label for="search_shipping_name">Shipping Name</label>
                                        <input type="text" name="search_shipping_name" id="search_shipping_name" placeholder="Enter Shipping Name..." class="order-search-bar"> </div>
                                    <div class="order-filter-group"> <label for="search_shipping_phone">Shipping Phone</label>
                                        <input type="tel" name="search_shipping_phone" id="search_shipping_phone" placeholder="Enter Shipping Phone..." class="order-search-bar"> </div>
                                    <div class="order-filter-group">
                                        <label for="search_shipping_address">Shipping Address</label>
                                        <input type="text" name="search_shipping_address" id="search_shipping_address" placeholder="Enter Address..." class="order-search-bar">
                                    </div>
                                    <div class="order-filter-group" style="flex: 0;"> <label>&nbsp;</label>
                                        <button type="submit" class="order-search-button">SEARCH</button> </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <p class="order-status-info">DISPLAYING ORDER LIST</p> <div class="order-table-scroll"> <table class="order-table"> <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer ID</th>
                                    <th>Order Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th style="white-space: normal;">Address</th> <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                </div>

                <div id="order-view-modal" class="order-modal"> <div class="order-modal-content"> <div class="order-modal-header"> <h1>Order Details</h1>
                            <span class="order-close-button" data-modal-id="order-view-modal">&times;</span> </div>
                        <div class="order-modal-body"> <p><strong>Order ID:</strong> <span id="view-order-id"></span></p>
                            <p><strong>Customer ID:</strong> <span id="view-customer-id"></span></p>
                            <p><strong>Order Date:</strong> <span id="view-order-date"></span></p>
                            <p><strong>Total Amount:</strong> <span id="view-total-amount"></span></p>
                            <p><strong>Status:</strong> <span id="view-status"></span></p>
                            <p><strong>Shipping Name:</strong> <span id="view-shipping-name"></span></p>
                            <p><strong>Shipping Phone:</strong> <span id="view-shipping-phone"></span></p>
                            <p><strong>Shipping Address:</strong> <span id="view-shipping-address"></span></p>
                            <hr>
                            <h4>Order Items:</h4>
                            <div id="view-order-items">
                                </div>
                        </div>
                    </div>
                </div>
                ';
        if (isset($permissionsMap["MANAGE_ORDER_STATUS"])) {
            $right .=
                '<form id="order-edit-form" action="#" method="POST">
                            <div id="order-edit-modal" class="order-modal"> <div class="order-modal-content"> <div class="order-modal-header"> <h1>Edit Order Status</h1>
                                        <span class="order-close-button" data-modal-id="order-edit-modal">&times;</span> </div>
                                    <div class="order-modal-body"> <input type="hidden" name="order_id" id="edit-order-id">
                                        <p><strong>Order ID:</strong> <span id="display-edit-order-id"></span></p>
                                        <p><strong>Current Status:</strong> <span id="display-current-status"></span></p>
                                        <div class="order-form-group"> <label for="order-edit-status">New Status</label>
                                            <select name="status" id="order-edit-status" required>
                                                <option value="pending">Pending</option>
                                                <option value="processing">Processing</option>
                                                <option value="shipped">Shipped</option>
                                                <option value="delivered">Delivered</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="order-modal-footer"> <button type="submit" class="order-save-button">Save Status</button> </div>
                                </div>
                            </div>
                        </form>';
        }
        $right .= '</div>';
    }
    if (isset($permissionsMap["CUSTOMERS"])) {
        $html .= "<li class='menu-item CUSTOMERS' data-value='CUSTOMERS'>
                    <lb><i class='fa-solid fa-user menu-icon'></i>Khách hàng</lb>   
                </li>";

        $right .= '<div class="CUSTOMERS content-ctn">
                    <div class="customer-content">
                        <div class="customer-header">
                            <h1>Customers</h1>';
        if (isset($permissionsMap["ADD_CUSTOMER"]))
            $right .=
                '<div class="customer-header-buttons">
                                <button class="customer-add-button" id="open-add-modal-button">ADD CUSTOMER</button>
                            </div>';

        $right .= '</div>
                        <form id="customer-search-form">
                            <div class="customer-search-section">
                                <div class="customer-search-title">SEARCH & FILTER CUSTOMERS</div>
                                <div class="customer-search-inputs">
                                    <div class="customer-search-row">
                                        <div class="customer-filter-group">
                                            <label for="search_id">ID</label>
                                            <input type="number" name="search_id" id="search_id" placeholder="Enter ID..." class="customer-search-bar">
                                        </div>
                                        <div class="customer-filter-group">
                                            <label for="customer_search_username">Username</label>
                                            <input type="text" name="search_username" id="customer_search_username" placeholder="Enter username..." class="customer-search-bar">
                                        </div>
                                        <div class="customer-filter-group">
                                            <label for="search_name">Name</label>
                                            <input type="text" name="search_name" id="search_name" placeholder="Enter Name..." class="customer-search-bar">
                                        </div>
                                        <div class="customer-filter-group">
                                            <label for="search_email">Email</label>
                                            <input type="email" name="search_email" id="search_email" placeholder="Enter Email..." class="customer-search-bar">
                                        </div>
                                        <div class="customer-filter-group">
                                            <label for="search_customer_status">Status</label>
                                            <select name="search_status" id="search_customer_status" class="customer-search-bar">
                                                <option value="">All Statuses</option>
                                                <option value="active">Active</option>
                                                <option value="locked">Locked</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="customer-search-row">
                                        <div class="customer-filter-group">
                                            <label for="search_phone">Phone</label>
                                            <input type="tel" name="search_phone" id="search_phone" placeholder="Enter Phone..." class="customer-search-bar">
                                        </div>
                                        <div class="customer-filter-group">
                                            <label for="search_address">Address</label>
                                            <input type="text" name="search_address" id="search_address" placeholder="Enter Address..." class="customer-search-bar">
                                        </div>
                                        <div class="customer-filter-group" style="flex: 0;"> <label>&nbsp;</label> <button type="submit" class="customer-search-button">SEARCH</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <p class="customer-status-info">DISPLAYING CUSTOMER LIST</p>

                        <div class="table-scroll">
                            <table class="customer-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                    </div>';
        if (isset($permissionsMap["EDIT_CUSTOMER"]))
            $right .= '
                    <form id="customer-edit-form">
                        <div id="customer-edit-modal" class="customer-modal"> <div class="customer-modal-content">
                                <div class="customer-modal-header">
                                    <h1>Edit Customer Information</h1>
                                    <span class="customer-close-button" data-modal-id="customer-edit-modal">&times;</span>
                                </div>
                                <div class="customer-modal-body">
                                    <input type="hidden" name="id" id="customer-edit-id">
                                    <div class="customer-form-group">
                                        <label for="customer-edit-name">Name</label>
                                        <input type="text" name="name" id="customer-edit-name" required>
                                    </div>
                                    <div class="customer-form-group">
                                        <label for="customer-edit-email">Email</label>
                                        <input type="email" name="email" id="customer-edit-email" required>
                                    </div>
                                    <div class="customer-form-group">
                                        <label for="customer-edit-phone">Phone</label>
                                        <input type="tel" name="phone" id="customer-edit-phone">
                                    </div>
                                    <div class="customer-form-group">
                                        <label for="customer-edit-address">Address</label>
                                        <textarea name="address" id="customer-edit-address"></textarea>
                                    </div>
                                </div>
                                <div class="customer-modal-footer">
                                    <button type="submit" class="customer-save-button">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>';

        if (isset($permissionsMap["ADD_CUSTOMER"]))
            $right .=
                '<form id="customer-add-form">
                        <div id="customer-add-modal" class="customer-modal"> <div class="customer-modal-content">
                                <div class="customer-modal-header">
                                    <h1>Add New Customer</h1>
                                    <span class="customer-close-button" data-modal-id="customer-add-modal">&times;</span>
                                </div>
                                <div class="customer-modal-body">
                                    <div class="customer-form-group">
                                        <label for="customer-add-username">Username</label>
                                        <input type="text" name="username" id="customer-add-username" required>
                                    </div>
                                    <div class="customer-form-group">
                                        <label for="customer-add-name">Name</label>
                                        <input type="text" name="name" id="customer-add-name" required>
                                    </div>
                                    <div class="customer-form-group">
                                        <label for="customer-add-email">Email</label>
                                        <input type="email" name="email" id="customer-add-email" required>
                                    </div>
                                    <div class="customer-form-group">
                                        <label for="customer-add-password">Password</label>
                                        <input type="password" name="password" id="customer-add-password" required>
                                    </div>
                                    <div class="customer-form-group">
                                        <label for="customer-add-phone">Phone</label>
                                        <input type="tel" name="phone" id="customer-add-phone">
                                    </div>
                                    <div class="customer-form-group">
                                        <label for="customer-add-address">Address</label>
                                        <textarea name="address" id="customer-add-address"></textarea>
                                    </div>
                                </div>
                                <div class="customer-modal-footer">
                                    <button type="submit" class="customer-save-button">Save Customer</button>
                                </div>
                            </div>
                        </div>
                    </form>';
        $right .= '
                </div>
            ';
    }
    if (isset($permissionsMap["EMPLOYEES"])) {
        $html .= "<li class='menu-item EMPLOYEES' data-value='EMPLOYEES'>
                    <lb><i class='fa-solid fa-user-tie menu-icon'></i>Nhân viên</lb>
                </li>";

        $right.= '<div class="EMPLOYEES content-ctn">
        <div class="employee-content">
            <div class="employee-header">
                <h1>Employees</h1>';
                if(isset($permissionsMap['ADD_EMPLOYEE']))
                $right.='
                <div class="employee-header-buttons">
                    <button class="employee-add-button" id="open-add-employee-modal-button">ADD EMPLOYEE</button>
                    <button class="employee-action-button" id="restore-employee-button">Restore</button>
                </div>';
                $right.='
            </div>


            <form id="employee-search-form">
                <div class="employee-search-section">
                    <div class="employee-search-title">SEARCH & FILTER EMPLOYEES</div>
                    <div class="employee-search-inputs">
                        <div class="employee-search-row">
                            <div class="employee-filter-group">
                                <label for="employee_search_username">Username</label>
                                <input type="text" name="search_username" id="employee_search_username" placeholder="Enter Username..." class="employee-search-bar">
                            </div>
                            <div class="employee-filter-group">
                                <label for="search_fullname">Fullname</label>
                                <input type="text" name="search_fullname" id="search_fullname" placeholder="Enter Fullname..." class="employee-search-bar">
                            </div>
                            <div class="employee-filter-group">
                                <label for="search_status">Status</label>
                                <select name="search_status" id="search_status1" class="employee-search-bar">
                                    <option value="">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="employee-filter-group">
                                <label for="search_role">Role</label>
                                    <select name="search_role" id="search_role" class="employee-search-bar">
                                    <option value="">All</option>
                                    <!-- Option sẽ được render bằng PHP hoặc JS -->
                                    </select>
                            </div>
                        </div>
                        <div class="employee-search-row" id="employee-SearchBtn-ctn">
                            <label>&nbsp;</label>
                            <button type="submit" class="employee-search-button" id="search-button">SEARCH</button>
                        </div>
                    </div>
                </div>
            </form>

            <p class="employee-status-info">DISPLAYING EMPLOYEE LIST</p>

            <div class="table-scroll">
                <table class="employee-table" id="employee-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Username</th>
                            <th>fullname</th>
                            <th>status</th>
                            <th>role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="abc">

                    </tbody>
                </table>
            </div>
        </div>';

            if(isset($permissionsMap['ADD_EMPLOYEE']))
                $right.='
        <form id="employee-add-form" action="#" method="POST">
        <div id="employee-add-modal" class="employee-modal">
            <div class="employee-modal-content">
                <div class="employee-modal-header">
                    <h1>Add New Employee</h1>
                    <span class="employee-close-button" data-modal-id="employee-add-modal">&times;</span>
                </div>
                <div class="employee-modal-body">
                    <div class="employee-form-group">
                        <label for="employee-add-name">Username</label>
                        <input type="text" name="username" id="employee-add-username" required>
                        <div id="error-message" style="color: red; font-size: 14px; margin-top: 5px;"></div>
                    </div>
                    <div class="employee-form-group">
                        <label for="employee-add-password">Password</label>
                        <input type="password" name="password" id="employee-add-password" required>
                    </div>
                    <div class="employee-form-group">
                        <label for="employee-add-phone">fullname</label>
                        <input type="text" name="fullname" id="employee-add-fullname">
                    </div>
                    <div class="employee-form-group">
                        <label for="employee-add-role">Role</label>
                        <select name="role" id="employee-add-role" required>
                            <!-- Option sẽ được render bằng PHP hoặc JS -->
                        </select>
                    </div>
                </div>
                <div class="employee-modal-footer">
                    <button type="submit" class="employee-save-button" id="save-employee-button">Save Employee</button>
                </div>
            </div>
        </div>
    </form>';
        if(isset($permissionsMap['EDIT_EMPLOYEE']))
        $right.='
        <!-- Form sửa nhân viên -->
        <form id="employee-edit-form" action="#" method="POST">
            <div id="employee-edit-modal" class="employee-modal">
                <div class="employee-modal-content">
                    <div class="employee-modal-header">
                        <h1>Edit Employee</h1>
                        <span class="employee-close-button" data-modal-id="employee-edit-modal">&times;</span>
                    </div>
                    <div class="employee-modal-body">
                        <input type="hidden" name="id" id="employee-edit-id">
                        <div class="employee-form-group">
                            <label for="employee-edit-username">Username</label>
                            <input type="text" name="username" id="employee-edit-username" required disabled>
                        </div>
                        <div class="employee-form-group">
                            <label for="employee-edit-password">Password (leave blank to keep current)</label>
                            <input type="password" name="password" id="employee-edit-password">
                        </div>
                        <div class="employee-form-group">
                            <label for="employee-edit-fullname">Fullname</label>
                            <input type="text" name="fullname" id="employee-edit-fullname">
                        </div>
                        <div class="employee-form-group">
                            <label for="employee-edit-role">Role</label>
                                <select name="role" id="employee-edit-role" required>
                                <!-- Option sẽ được render bằng PHP hoặc JS -->
                                </select>
                        </div>
                        <div class="employee-form-group">
                            <label for="employee-edit-status">Status</label>
                            <select name="status" id="employee-edit-status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="employee-modal-footer">
                        <button type="submit" class="employee-save-button" id="save-employee-edit-button">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- Modal quản lý quyền nhân viên -->
        <div id="employee-permission-modal" class="employee-modal" style="display:none;">
            <div class="employee-modal-content" style="min-width:500px;max-width:500px;">
                <div class="employee-modal-header">
                    <h1>Phân quyền nhân viên</h1>
                    <span class="employee-close-button" id="close-permission-modal" style="cursor:pointer;">&times;</span>
                </div>
                <div class="employee-modal-body">
                    <form id="employee-permission-form">
                        <input type="hidden" name="employee_id" id="permission-employee-id">
                        <div id="permission-list">
                            <!-- Danh sách quyền sẽ được render ở đây bằng JS -->
                        </div>
                    </form>
                </div>
                <div class="employee-modal-footer">
                    <button type="button" id="save-permission-button" class="employee-save-button">Lưu quyền</button>

                </div>
            </div>
        </div>';
        if(isset($permissionsMap['ADD_EMPLOYEE']))
        $right.='
        <!-- Modal restore nhân viên -->
        <div id="employee-restore-modal" class="employee-modal" style="display:none;">
            <div class="employee-modal-content" style="min-width:350px;max-width:400px;">
                <div class="employee-modal-header">
                    <h1>Khôi phục nhân viên đã xóa</h1>
                    <span class="employee-close-button" id="close-restore-modal" style="cursor:pointer;">&times;</span>
                </div>
                <div class="employee-modal-body">
                    <table id="restore-employee-table" border="1" style="width:100%;text-align:center;">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Username</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="restore-employee-tbody">
                            <!-- Dữ liệu sẽ được render bằng JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>';
        $right.='
    </div>';
    }
    if (isset($permissionsMap["SUPPLIERS"])) {
        $html .= "<li class='menu-item SUPPLIERS' data-value='SUPPLIERS'>
                    <lb><i class='fa-solid fa-truck menu-icon'></i>Nhà cung cấp</lb>
                </li>";

        $right .= '<div class="SUPPLIERS content-ctn">
        <div class="sr-container">
            <div class="sr-main">
                <div class="sr-topbar">
                    <div class="search">
                        <h1 class="sr-title">SUPPLIER & RECEIPT MANAGEMENT</h1>
                    </div>
                </div>
                <div class="sm-card-box">
                    <div class="sm-card">
                        <div>
                            <div class="sm-numbers" id="total-suppliers"></div>
                            <div class="sm-card-name">Total Supplier</div>
                        </div>
                        <div class="sm-icon-box">
                            <ion-icon name="eye-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="sm-card">
                        <div>
                            <div class="sm-numbers" id="total-receipts"></div>
                            <div class="sm-card-name">Total Receipts</div>
                        </div>
                        <div class="sm-icon-box">
                            <ion-icon name="cart-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="sm-card">
                        <div>
                            <div class="sm-numbers" id="total-cost"></div>
                            <div class="sm-card-name">Total Cost</div>
                        </div>
                        <div class="sm-icon-box">
                            <ion-icon name="cash-outline"></ion-icon>
                        </div>
                    </div>
                </div>
                <div class="sr-table-switch">
                    <button id="sr-switch-supplier" class="active">Supplier</button>';
        if (isset($permissionsMap['VIEW_RECEIPT']))
            $right .= '
                    <button id="sr-switch-receipt">Receipt</button>';
        $right .= '
                </div>
                <div id="supplier-details" class="sr-supplier-details">
                    <div class="sr-recent-orders">
                        <div class="sr-card-header">
                            <h2>Recent Suppliers</h2>
                            <div class="search-container">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="supplier-search" placeholder="Tìm kiếm nhà cung cấp...">
                                <ul id="suggestions-list" class="suggestions-list"></ul>
                            </div>';
        if (isset($permissionsMap['ADD_SUPPLIER'])) {
            $right .= '
                                <button id="add-supplier-btn">Add Supplier</button>';
        }
        $right .= '
                        </div>
                        <table class="sr-order-table">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Name</td>
                                    <td>Contact</td>
                                    <td>Email</td>
                                    <td>Phone</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody id="supplier-list">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="receipt-details" class="sr-receipt-details" style="display: none;">
                    <div class="sr-recent-orders">
                        <div class="sr-card-header">
                            <h2>Recent Receipts</h2>';
        if (isset($permissionsMap['ADD_RECEIPT']))
            $right .= '
                            <button id="add-receipt-btn">Add Receipt</button>';
        $right .= '
                        </div>
                        <form id="receipt-filter-form" style="margin-bottom:10px; display:flex;gap:10px;align-items:end;width:100%;justify-content:space-between">
                            <div>
                                <label for="filter-receiptsBySupplier">Supplier:</label>
                                <select id="filter-receiptsBySupplier" name="supplier_id">
                                    <option value="">All</option>
                                    <!-- Option sẽ được load bằng JS -->
                                </select>
                            </div>
                            <div>
                                <label for="filter-date-from">From:</label>
                                <input type="date" id="filter-date-from" name="date_from">
                            </div>
                            <div>
                                <label for="filter-date-to">To:</label>
                                <input type="date" id="filter-date-to" name="date_to">
                            </div>
                            <button type="submit" class="receipt-search-button">Filter</button>
                        </form>
                        <table class="sr-order-table">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Date</td>
                                    <td>Total Amount</td>
                                    <td>Supplier</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody id="receipt-list">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>';
        if (isset($permissionsMap['ADD_SUPPLIER']))
            $right .= '
        <!-- ADD SUPPLIER FORM -->
        <div class="supplier-add-ctn" id="supplier-add-ctn">
            <div class="supplier-form-container" id="supplier-form-container"> 
                <button class="close-supplier-form"><i class="fa-solid fa-rectangle-xmark"></i></button>
                <h2 class="supplier-form-title">ADD NEW SUPPLIER</h2> 
                <form action="" method="POST" id="supplier-add-form">
                    <div class="form-group">
                        <label for="add-ten" class="form-label">Tên:</label>
                        <input type="text" id="add-ten" name="ten" class="form-input" placeholder="Nhập tên nhà cung cấp" required>
                    </div>
                    <div class="form-group">
                        <label for="add-contact" class="form-label">Người Liên Hệ:</label>
                        <input type="text" id="add-contact" name="contact" class="form-input" placeholder="Nhập tên người liên hệ">
                    </div>
                    <div class="form-group">
                        <label for="add-email" class="form-label">Email:</label>
                        <input type="email" id="add-email" name="email" class="form-input" placeholder="Nhập địa chỉ email. vd:abc@gmail.com" onblur="validateEmail(this)">
                        <span id="email-error" style="color:red;display:none;">Email không hợp lệ</span>
                    </div>
                    <div class="form-group">
                        <label for="add-phone" class="form-label">Số Điện Thoại:</label>
                        <input type="tel" id="add-phone" name="phone" class="form-input" placeholder="Nhập số điện thoại. vd: +84123456789" onblur="validatePhone(this)">
                        <span id="phone-error" style="color:red;display:none;">Số điện thoại không hợp lệ</span>
                    </div>
                    <div class="form-group">
                        <label for="add-address" class="form-label">Địa Chỉ:</label>
                        <input type="text" id="add-address" name="address" class="form-input" placeholder="Nhập địa chỉ">
                    </div>
                    <button type="submit" class="form-button">Thêm Nhà Cung Cấp</button>
                </form>';
        $right .= '
            </div>
        </div>';
        if (isset($permissionsMap['EDIT_SUPPLIER']))
            $right .= '
        <!-- MODIFY SUPPLIER FORM -->
        <div class="supplier-modify-ctn">
            <div class="supplier-form-container"> 
                <button class="close-supplier-modify-form"><i class="fa-solid fa-rectangle-xmark"></i></button>
                <h2 class="supplier-modify-form-title">MODIFY SUPPLIER</h2> 
                <form id="supplier-modify-form" method="POST">
                    <input type="hidden" id="form-mode" name="form-mode" value="edit">
                    <input type="hidden" id="edit-id" name="edit-id" value="">
                    <div class="form-group">
                        <label for="modify-id" class="form-label">ID:</label>
                        <input type="number" id="modify-id" name="id" class="form-input" placeholder="Nhập ID (số)" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modify-ten" class="form-label">Tên:</label>
                        <input type="text" id="modify-ten" name="ten" class="form-input" placeholder="Nhập tên nhà cung cấp" required>
                    </div>
                    <div class="form-group">
                        <label for="modify-contact" class="form-label">Người Liên Hệ:</label>
                        <input type="text" id="modify-contact" name="contact" class="form-input" placeholder="Nhập tên người liên hệ">
                    </div>
                    <div class="form-group">
                        <label for="modify-email" class="form-label">Email:</label>
                        <input type="email" id="modify-email" name="email" class="form-input" placeholder="Nhập địa chỉ email. vd:abc@gmail.com" onblur="validateEmail(this)">
                        <span id="emailm-error" style="color:red;display:none;">Email không hợp lệ</span>
                    </div>
                    <div class="form-group">
                        <label for="modify-phone" class="form-label">Số Điện Thoại:</label>
                        <input type="tel" id="modify-phone" name="phone" class="form-input" placeholder="Nhập số điện thoại. vd: +84123456789" onblur="validatePhone(this)">
                        <span id="phonem-error" style="color:red;display:none;">Số điện thoại không hợp lệ</span>
                    </div>
                    <div class="form-group">
                        <label for="modify-address" class="form-label">Địa Chỉ:</label>
                        <input type="text" id="modify-address" name="address" class="form-input" placeholder="Nhập địa chỉ">
                    </div>
                    <button type="submit" class="modify-form-button">Cập nhật Nhà Cung Cấp</button>
                </form>';
        $right .= '
            </div>
        </div>';
        if (isset($permissionsMap['ADD_RECEIPT']))
            $right .= '
        <!-- ADD RECEIPT -->
        <div class="receipt-add-form" id="receipt-add-form">
            <div class="receipt-main-form-container">
                <button type="button" class="close-receipt-main-form" id="close-receipt-main-form"><i class="fa-solid fa-rectangle-xmark"></i></button>
                <h2 class="receipt-main-form-title">THÊM PHIẾU NHẬP</h2>
                <label for="supplier-id">Chọn nhà cung cấp:</label>
                <input type="text" id="supplier-add-search" placeholder="Nhập tên nhà cung cấp...">
                <ul id="suggestions-add-list" class="suggestions-list"></ul>
                <label for="percent">Nhập % chiết khấu:</label>
                <input type="number" id="percent" name="discount-percent">
                <button type="button" id="open-receipt-detail-form-btn">Thêm</button>
            </div>
        </div>';
        if (isset($permissionsMap['ADD_RECEIPT']))
            $right .= '
        <div class="receipt-detail-form-ctn">
            <form class="receipt-detail-form-container">
                <button type="button" class="close-receipt-detail-form">
                    <i class="fa-solid fa-rectangle-xmark"></i>
                </button>
                <h2 class="receipt-detail-form-title">THÊM CHI TIẾT PHIẾU NHẬP</h2>
                <div class="receipt-info">
                    <div><strong>Trạng thái:</strong> Chưa hoàn thành</div>
                    <div><strong>Nhà cung cấp:</strong> AZ Việt Nam</div>
                    <div><strong>Nhân viên:</strong> Nguyễn Thanh Sang</div>
                    <div><strong>Ngày tạo phiếu:</strong> 2025-04-19</div>
                    <div><strong>Chiết khấu:</strong> 10%</div>
                </div>
                <table class="receipt-product-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên giày</th>
                            <th>Size & Số lượng</th>
                            <th>Đơn giá nhập</th>
                            <th>Giá bán</th>
                            <th>Thành tiền</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="receipt-product-rows">
                        <tr>
                            <td class="stt">1</td>
                            <td>
                                <input type="text" id="product-search" class="idproduct-search" placeholder="Nhập tên sản phẩm..." data-product-id="">
                                <ul id="suggestions-add-list" class="suggestions-product-list"></ul>
                            </td>
                            <td>
                                <div class="sizes-wrapper"></div>
                                <button type="button" class="add-size-btn">+ Thêm size</button>
                            </td>
                            <td><input type="number" name="price[0]" class="price" value="0" min="0" step="any"></td>
                            <td><span class="sell-price"></span> </td>
                            <td><span class="total-price"></span> </td>
                            <td><button type="button" class="remove-row-btn">🗑️</button></td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-actions">
                    <button type="button" class="add-row-btn">+ Thêm dòng</button>
                    <button type="submit" class="submit-btn">Thêm phiếu nhập</button>
                </div>
                <div class="receipt-total">
                    <strong>Tổng tiền:</strong> <span id="total-amount"></span>
                </div>
            </form>
        </div>';
        $right .= '
    </div>';
    }

    $html .= "</ul>
            </div>
                <div id='admin-account-ctn'>
                    <div id='account'>
                        <lb>
                            <p id='admin-username'><i class='fa-solid fa-at admin-username-icon'></i>" . htmlspecialchars($user['fullname'], ENT_QUOTES, 'UTF-8') . "<p>
                            <p id='admin-role'><i class='fa-solid fa-hashtag admin-role-icon'></i>" . htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') . "</p>
                        </lb>
                        <div id='change-password-btn'>Đổi mật khẩu</div>
                    </div>
                    <form id='change-password-form'>
                        <div id='change-password-form-x' class='x-ctn'><i class='fa-regular fa-circle-xmark x'></i></div>
                        <label>Mật khẩu cũ:</label>
                        <input name='old-password' type='password' placeholder='Nhập mật khẩu cũ'>
                        <label>Mật khẩu mới:</label>
                        <input name='new-password' type='password' placeholder='Nhập mật khẩu mới'>
                        <label>Xác nhận:</label>
                        <input name='new-password-confirm' type='password' placeholder='Nhập lại mật khẩu mới'>
                        <div id='center-wrapper'><button type='submit'>Lưu</button></div>
                    </form>
                    <div id='admin-logout-button' class='menu-item'>
                        <lb><i class='fa-solid fa-sign-out fa-flip-horizontal menu-icon'></i></i>Đăng xuất</lb>
                    </div>
                </div>
            </div>";

    $html .= $right . '</div>';
    return $html;
}
