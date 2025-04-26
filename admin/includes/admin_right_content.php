<div id="right_content">
    <div class="STATS content-ctn">
        <div id="stats-ctn">
            <div id="chart-ctn">
                <canvas id="status-chart" width="300" height="150"></canvas>
                <div id="chart-input-warning" class=""><i class="fa-solid fa-triangle-exclamation"></i>Không có dữ liệu!</div>
            </div>
            
        </div> 
        <form id="stats-menu-option-form">
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
            <div class="stats-option-ctn disabled" id="stats-time">
                <label>Thời gian:</label>
                <div id="time-range-option-ctn" class="stats-option-ctn border">
                    <div id="by-day-option-ctn" class="stats-option">
                        <label for="by-day-from">Từ ngày:</label>
                        <input name="by-day-from" id="by-day-from" type="date" value="<?php echo date("Y-m-d");?>" class="stats-option-slt">
                        <label for="by-day-to">Đến hết ngày:</label>
                        <input name="by-day-to" id="by-day-to" type="date" value="<?php echo date("Y-m-d");?>" class="stats-option-slt">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="PRODUCTS content-ctn">
        
    </div>
    <div class="ORDERS content-ctn">
        <div class="order-content"> <div class="order-header">
                <h1>Orders</h1>
                </div>

            <form method="POST" action="#" id="order-search-form">
                 <div class="order-search-section"> <div class="order-search-title">SEARCH & FILTER ORDERS</div> <div class="order-search-inputs"> <div class="order-search-row"> <div class="order-filter-group"> <label for="search_order_id">Order ID</label>
                                 <input type="number" name="search_order_id" id="search_order_id" placeholder="Enter Order ID..." class="order-search-bar"> </div>
                             <div class="order-filter-group"> <label for="search_customer_id">Customer ID</label>
                                 <input type="number" name="search_customer_id" id="search_customer_id" placeholder="Enter Customer ID..." class="order-search-bar"> </div>
                             <div class="order-filter-group"> <label for="search_status">Status</label>
                                <select name="search_status" id="search_status" class="order-search-bar"> <option value="">All Statuses</option>
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

        <form id="order-edit-form" action="#" method="POST">
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
        </form>
    </div>
    <div class="CUSTOMERS content-ctn">
        <div class="customer-content">
            <div class="customer-header">
                <h1>Customers</h1>
                <div class="customer-header-buttons">
                    <button class="customer-add-button" id="open-add-modal-button">ADD CUSTOMER</button>
                </div>
            </div>

            <form method="POST" action="#" id="customer-search-form">
                 <div class="customer-search-section">
                     <div class="customer-search-title">SEARCH & FILTER CUSTOMERS</div>
                     <div class="customer-search-inputs">
                         <div class="customer-search-row">
                             <div class="customer-filter-group">
                                 <label for="search_id">ID</label>
                                 <input type="number" name="search_id" id="search_id" placeholder="Enter ID..." class="customer-search-bar">
                             </div>
                             <div class="customer-filter-group">
                                 <label for="search_name">Name</label>
                                 <input type="text" name="search_name" id="search_name" placeholder="Enter Name..." class="customer-search-bar">
                             </div>
                             <div class="customer-filter-group">
                                 <label for="search_email">Email</label>
                                 <input type="email" name="search_email" id="search_email" placeholder="Enter Email..." class="customer-search-bar">
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>


                        </tbody>
                </table>
            </div>
        </div>

        <form id="customer-edit-form" action="#" method="POST">
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
        </form>

        <form id="customer-add-form" action="#" method="POST">
            <div id="customer-add-modal" class="customer-modal"> <div class="customer-modal-content">
                    <div class="customer-modal-header">
                        <h1>Add New Customer</h1>
                         <span class="customer-close-button" data-modal-id="customer-add-modal">&times;</span>
                    </div>
                    <div class="customer-modal-body">
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
        </form>
    </div>

    </div>
    <div class="CUSTOMERS content-ctn">
    <div class="customer-content">
    <div class="customer-header">
        <h1>Customers</h1>
        <!-- <p class="customer-help-text">View, add, edit customer information. <a href="#">Need help?</a></p> -->
        <div class="customer-header-buttons">
            <button class="customer-add-button" id="open-add-modal-button">ADD CUSTOMER</button>
        </div>
    </div>

    <form method="POST" action="#" id="customer-search-form"> <div class="customer-search-section">
    <div class="customer-search-title">SEARCH & FILTER CUSTOMERS</div>
        <div class="customer-search-inputs">
            <div class="customer-search-row">
                <div class="customer-filter-group">
                    <label for="search_id">ID</label>
                    <input type="number" name="search_id" id="search_id" placeholder="Enter ID..." class="customer-search-bar">
                </div>
                <div class="customer-filter-group">
                    <label for="search_name">Name</label>
                    <input type="text" name="search_name" id="search_name" placeholder="Enter Name..." class="customer-search-bar">
                </div>
                <div class="customer-filter-group">
                    <label for="search_email">Email</label>
                    <input type="email" name="search_email" id="search_email" placeholder="Enter Email..." class="customer-search-bar">
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
                
                </tbody>
        </table>
    </div>
</div>

<form id="customer-edit-form" action="#" method="POST">
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
</form>

<form id="customer-add-form" action="#" method="POST">
    <div id="customer-add-modal" class="customer-modal"> <div class="customer-modal-content">
            <div class="customer-modal-header">
                <h1>Add New Customer</h1>
                 <span class="customer-close-button" data-modal-id="customer-add-modal">&times;</span>
            </div>
            <div class="customer-modal-body">
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
</form>
    </div>
    <div class="EMPLOYEES content-ctn">
    EMPLOYEES
    </div>
    <div class="SUPPLIERS content-ctn">
    SUPPLIERS
    </div>
</div>