// --- Tệp: admin/js/order_scripts.js ---

// Đảm bảo DOM đã tải xong trước khi chạy script
function orderfunc() {

    // Lấy các phần tử DOM cần thiết cho trang Orders
    let orderSearchForm = document.getElementById('order-search-form');
    let orderTableBody = document.querySelector('.order-table tbody'); // Target tbody của bảng order
    let orderStatusInfo = document.querySelector('.order-status-info');
    // Thêm các modal và nút nếu cần xử lý ở đây
    // const orderViewModal = document.getElementById('order-view-modal');
    // const orderEditModal = document.getElementById('order-edit-modal');

    // --- Hàm hiển thị thông báo (có thể dùng chung hoặc tạo riêng) ---
    // function showMessage(message, type = 'info') { ... } // Sử dụng hàm showMessageDialog đã có nếu muốn

    // --- Hàm tạo HTML cho một hàng trong bảng Order ---
    function createOrderRowHtml(order, canEdit) {
        const customerId = order.customer_id || 'N/A';
        const orderDate = order.order_date
            ? new Date(order.order_date).toLocaleString('vi-VN')
            : 'N/A';
        const totalAmount = order.total_amount
            ? parseFloat(order.total_amount).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })
            : 'N/A';
        const status = order.status || 'N/A';
        const shippingName = escapeHtml(order.shipping_name || 'N/A');
        const shippingPhone = escapeHtml(order.shipping_phone || '');
        const shippingAddress = escapeHtml(order.shipping_address || 'N/A');

        // CHỈ ẩn nút edit nếu không có quyền
        const actionButtons = `
            <button class="order-view-button" data-id="${order.order_id}" title="View Details">
                <i class="fas fa-eye"></i>
            </button>
            ${canEdit ? `
            <button class="order-edit-button" data-id="${order.order_id}" title="Edit Order Status">
                <i class="fas fa-edit"></i>
            </button>` : ''}
        `;

        return `
            <tr>
                <td>${order.order_id}</td>
                <td>${customerId}</td>
                <td>${orderDate}</td>
                <td style="text-align: right;">${totalAmount}</td>
                <td>${status}</td>
                <td>${shippingName}</td>
                <td>${shippingPhone}</td>
                <td>${shippingAddress}</td>
                <td style="text-align: center;">${actionButtons}</td>
            </tr>
        `;
    }


    // --- Hàm cập nhật bảng Order với dữ liệu mới ---
    function updateOrderTable(orders) {
        if (!orderTableBody) {
            console.error("Lỗi: Không tìm thấy tbody của bảng order.");
            return;
        }

        // Xóa bảng trước khi check quyền
        orderTableBody.innerHTML = '';

        checkPermission('MANAGE_ORDER_STATUS')
            .then(canEdit => {
                try {
                    if (orders && orders.length > 0) {
                        let allRowsHtml = '';
                        orders.forEach(order => {
                            allRowsHtml += createOrderRowHtml(order, canEdit);
                        });
                        orderTableBody.innerHTML = allRowsHtml;

                        // Gắn lại sự kiện cho các nút sau khi render xong
                        attachOrderActionListeners();
                    } else {
                        orderTableBody.innerHTML = `<tr><td colspan="9" style="text-align: center;">No orders found matching your criteria.</td></tr>`;
                    }
                } catch (err) {
                    console.error("Lỗi khi xử lý và hiển thị đơn hàng:", err);
                }
            })
            .catch(error => {
                console.error("Lỗi khi kiểm tra quyền:", error);
            });
    }


    // --- Hàm xử lý sự kiện Submit Form Tìm Kiếm Order ---
    if (orderSearchForm) {
        orderSearchForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Ngăn form gửi theo cách truyền thống

            const formData = new FormData(orderSearchForm);
            const searchUrl = 'includes/right_content/orders/searchOrders.php'; // URL backend xử lý tìm kiếm order

            if (orderStatusInfo) {
                orderStatusInfo.textContent = 'Searching orders...';
            }
            // Hiển thị loading indicator nếu muốn

            fetch(searchUrl, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let result = JSON.parse(responseData);
                        if (result.status === 'success') {
                            updateOrderTable(result.data); // Cập nhật bảng với dữ liệu order

                            // Cập nhật dòng trạng thái
                            if (orderStatusInfo) {
                                let filterInfo = [];
                                // Lấy thông tin filter đang áp dụng (ví dụ)
                                for (let [key, value] of formData.entries()) {
                                    if (value !== '') {
                                        let cleanKey = key.startsWith('search_') ? key.substring(7).replace(/_/g, ' ') : key.replace(/_/g, ' '); // Format key cho dễ đọc
                                        filterInfo.push(`${cleanKey}: ${value}`);
                                    }
                                }
                                orderStatusInfo.textContent = `DISPLAYING ORDER LIST ${filterInfo.length > 0 ? '(Filtered by: ' + filterInfo.join(', ') + ')' : '(All Orders)'}`;
                            }
                        } else {
                            console.error('Search Error from server:', result.message);
                            if (orderStatusInfo) {
                                orderStatusInfo.textContent = `Error searching orders: ${result.message}`;
                            }
                            if (orderTableBody) {
                                orderTableBody.innerHTML = `<tr><td colspan="9" style="text-align: center;">Error: ${result.message}</td></tr>`; // Colspan = 9
                            }
                        }
                    }
                    catch {
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    console.error('Fetch function error:', error);
                    if (orderStatusInfo) {
                        orderStatusInfo.textContent = `Error during search: ${error.message}`;
                    }
                    if (orderTableBody) {
                        orderTableBody.innerHTML = `<tr><td colspan="9" style="text-align: center;">Fetch Error: ${error.message}</td></tr>`; // Colspan = 9
                    }
                })
                .finally(() => {
                    // Ẩn loading indicator nếu có
                });
        });

        // Tự động tìm kiếm khi trang tải xong (hoặc khi tab Orders được chọn)
        // Bạn có thể gọi submit() nếu muốn hiển thị tất cả đơn hàng ban đầu
        // orderSearchForm.dispatchEvent(new Event('submit')); // Hoặc gọi hàm fetch trực tiếp không cần form data
        // Hoặc tốt hơn là tạo hàm riêng để load dữ liệu ban đầu
        loadInitialOrders();
    } else {
        console.error("Lỗi: Không tìm thấy form với ID 'order-search-form'");
    }

    // --- Hàm để load dữ liệu order ban đầu ---
    function loadInitialOrders() {
        if (orderStatusInfo) orderStatusInfo.textContent = 'Loading orders...';
        fetch('includes/right_content/orders/searchOrders.php', { method: 'POST' }) // Gửi POST rỗng để lấy tất cả
            .then(response => response.text())
            .then(responseData => {
                try {
                    let result = JSON.parse(responseData);
                    if (result.status === 'success') {
                        updateOrderTable(result.data);
                        if (orderStatusInfo) orderStatusInfo.textContent = 'DISPLAYING ORDER LIST (All Orders)';
                    } else {
                        throw new Error(result.message || 'Unknown error loading orders');
                    }
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(error => {
                console.error('Error loading initial orders:', error);
                if (orderStatusInfo) orderStatusInfo.textContent = `Error: ${error.message}`;
                if (orderTableBody) orderTableBody.innerHTML = `<tr><td colspan="9" style="text-align: center;">Could not load orders.</td></tr>`;
            });
    }

    // --- Hàm gắn sự kiện cho các nút View/Edit trong bảng Order ---
    function attachOrderActionListeners() {
        const viewButtons = orderTableBody.querySelectorAll('.order-view-button');
        const editButtons = orderTableBody.querySelectorAll('.order-edit-button');

        viewButtons.forEach(button => {
            // Xóa listener cũ nếu có để tránh gắn nhiều lần (an toàn hơn)
            button.replaceWith(button.cloneNode(true));
        });
        // Gắn lại listener cho nút vừa clone
        orderTableBody.querySelectorAll('.order-view-button').forEach(button => {
            button.addEventListener('click', handleViewOrder);
        });


        editButtons.forEach(button => {
            button.replaceWith(button.cloneNode(true));
        });
        orderTableBody.querySelectorAll('.order-edit-button').forEach(button => {
            button.addEventListener('click', handleEditOrder);
        });
    }

    // --- Hàm xử lý khi nhấn nút View Order ---
    function handleViewOrder(event) {
        const button = event.currentTarget; // Dùng currentTarget an toàn hơn
        const orderId = button.dataset.id;
        // Lấy modal view
        const viewModal = document.getElementById('order-view-modal');
        if (!viewModal) return;

        // TODO: Fetch chi tiết đơn hàng từ backend bằng orderId
        // Ví dụ: fetch(`includes/getOrderDetails.php?id=${orderId}`)
        // Sau khi fetch thành công:
        // 1. Điền dữ liệu vào các span trong #order-view-modal
        //    document.getElementById('view-order-id').textContent = orderDetails.order_id;
        //    ... (điền các thông tin khác) ...
        //    document.getElementById('view-order-items').innerHTML = createOrderItemsHtml(orderDetails.items); // Tạo HTML cho danh sách sản phẩm
        function createOrderItemsHtml(items) {
            if (!items || items.length === 0) {
                return '<p>No items found for this order.</p>';
            }

            let tableHtml = `
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f9f9f9; text-align: left;">Image</th>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f9f9f9; text-align: left;">Product</th>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f9f9f9; text-align: left;">Size</th>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f9f9f9; text-align: center;">Quantity</th>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f9f9f9; text-align: right;">Price</th>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f9f9f9; text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            items.forEach(item => {
                const imageUrl = item.product_image || 'imgs/placeholder.png'; // Đường dẫn ảnh mặc định nếu không có
                const productName = escapeHtml(item.product_name || 'N/A');
                const productSize = escapeHtml(item.product_size || 'N/A');
                const quantity = parseInt(item.quantity) || 0;
                const itemPrice = parseFloat(item.item_price) || 0;
                const totalItemPrice = quantity * itemPrice;

                const formattedItemPrice = itemPrice.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
                const formattedTotalItemPrice = totalItemPrice.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });

                tableHtml += `
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><img src="${imageUrl}" alt="${productName}" style="width: 50px; height: 50px; object-fit: cover;"></td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${productName}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${productSize}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">${quantity}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">${formattedItemPrice}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">${formattedTotalItemPrice}</td>
                    </tr>
                `;
            });

            tableHtml += `
                    </tbody>
                </table>
            `;
            return tableHtml;
        }

        // ---- GIẢ LẬP DỮ LIỆU ĐỂ MỞ MODAL ----
        // (Bạn cần thay thế phần này bằng fetch thật)
        const row = button.closest('tr');
        if (row) {
            const cells = row.querySelectorAll('td');
            document.getElementById('view-order-id').textContent = cells[0].textContent;
            document.getElementById('view-customer-id').textContent = cells[1].textContent;
            document.getElementById('view-order-date').textContent = cells[2].textContent;
            document.getElementById('view-total-amount').textContent = cells[3].textContent;
            document.getElementById('view-status').textContent = cells[4].textContent;
            document.getElementById('view-shipping-name').textContent = cells[5].textContent;
            document.getElementById('view-shipping-phone').textContent = cells[6].textContent;
            document.getElementById('view-shipping-address').textContent = cells[7].textContent;
        }
        else {
            console.warn("Could not find table row to prefill modal.");
            // Có thể reset các trường nếu không tìm thấy row
        }

        // Lấy phần tử để hiển thị item và hiển thị loading
        const itemsContainer = document.getElementById('view-order-items');
        if (!itemsContainer) return;
        itemsContainer.innerHTML = '<p><em>Loading order items...</em></p>'; // Hiển thị loading

        // Fetch chi tiết các mục hàng từ backend
        fetch(`includes/right_content/orders/getOrderDetails.php?id=${orderId}`) // Sử dụng GET
            .then(response => response.text())
            .then(responseData => {
                try {
                    let result = JSON.parse(responseData);
                    if (result.status === 'success') {
                        // Tạo HTML cho danh sách items và cập nhật modal
                        itemsContainer.innerHTML = createOrderItemsHtml(result.data);
                    } else {
                        // Hiển thị lỗi nếu fetch không thành công
                        itemsContainer.innerHTML = `<p style="color: red;">Error loading items: ${result.message}</p>`;
                        console.error('Error fetching order details:', result.message);
                    }
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(error => {
                itemsContainer.innerHTML = `<p style="color: red;">Error: ${error.message}</p>`;
                console.error('Fetch error:', error);
            })
            .finally(() => {
                // Mở modal sau khi fetch xong (dù thành công hay lỗi)
                openModal(viewModal);
            });
    }

    function capitalizeFirstLetter(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // --- Hàm xử lý khi nhấn nút Edit Order ---
    function handleEditOrder(event) {
        const button = event.currentTarget;
        const orderId = button.dataset.id;

        // Lấy modal edit
        const editModal = document.getElementById('order-edit-modal');
        if (!editModal) return;

        // Lấy trạng thái hiện tại từ bảng (hoặc fetch lại nếu cần)
        const row = button.closest('tr');
        let currentStatus = 'N/A';
        if (row) {
            const statusCell = row.cells[4]; // Ô thứ 5 chứa status
            if (statusCell) {
                currentStatus = statusCell.textContent;
            }
        }

        let status = ['cancelled', 'pending', 'processing', 'shipped', 'delivered'];
        let statusSelect = document.getElementById('order-edit-status');

        let index = status.indexOf(currentStatus);
        if (index != -1) {
            let op = '';
            if (currentStatus != 'cancelled') {
                let result = status.slice(index);

                result.forEach(e => {
                    op += '<option value="' + e + '">' + capitalizeFirstLetter(e) + '</option>'
                });
            }
            else {
                op = '<option value="cancelled">Cancelled</option>';
            }
            statusSelect.innerHTML = op;
        }

        // Điền order ID và trạng thái hiện tại vào modal
        document.getElementById('edit-order-id').value = orderId;
        document.getElementById('display-edit-order-id').textContent = orderId;
        document.getElementById('display-current-status').textContent = currentStatus;
        // Đặt giá trị mặc định cho select là trạng thái hiện tại
        if (statusSelect) {
            statusSelect.value = currentStatus;
        }


        // Mở modal
        openModal(editModal); // Giả sử bạn có hàm openModal
    }

    // --- Xử lý submit form Edit Order (ví dụ: cập nhật status) ---
    const orderEditForm = document.getElementById('order-edit-form');
    if (orderEditForm) {
        orderEditForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(orderEditForm);
            const orderId = formData.get('order_id');
            const newStatus = formData.get('status');


            // TODO: Fetch đến backend để cập nhật status
            // Ví dụ: fetch('includes/updateOrderStatus.php', { method: 'POST', body: formData })
            // Sau khi fetch thành công:
            // 1. Đóng modal
            // 2. Hiển thị thông báo thành công
            // 3. Cập nhật lại dòng tương ứng trong bảng (hoặc load lại toàn bộ bảng)

            // ---- GIẢ LẬP ----
            closeModal(document.getElementById('order-edit-modal'));
            showMessageDialog('<p><i class="fa-regular fa-circle-check green icon"></i>Order status updated successfully (simulated)!</p>');
            // Tìm và cập nhật cell status trong bảng
            const rowToUpdate = orderTableBody.querySelector(`.order-edit-button[data-id="${orderId}"]`)?.closest('tr');
            if (rowToUpdate) {
                const statusCell = rowToUpdate.cells[4];
                if (statusCell) {
                    statusCell.textContent = newStatus;
                }
            }
            // ---- KẾT THÚC GIẢ LẬP ----
        });
    }


    // --- Hàm tiện ích (có thể copy từ customer_scripts.js hoặc dùng chung) ---
    // Hàm mở modal
    function openModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'flex'; // Sử dụng flex để căn giữa
        }
    }

    // Hàm đóng modal
    function closeModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'none';
        }
    }

    // Gắn sự kiện cho tất cả các nút đóng modal trên trang Order
    const closeButtons = document.querySelectorAll('.ORDERS .order-close-button'); // Chỉ chọn nút đóng trong phần ORDERS
    closeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-modal-id');
            const modalToClose = document.getElementById(modalId);
            closeModal(modalToClose);
        });
    });

    // Hàm escape HTML đơn giản để tránh XSS cơ bản khi hiển thị dữ liệu
    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') return unsafe; // Trả về nguyên gốc nếu không phải chuỗi
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Khởi tạo: load dữ liệu ban đầu khi tab Orders được hiển thị
    // Cần có cơ chế để biết khi nào tab Orders active để gọi loadInitialOrders()
    // Ví dụ, nếu bạn dùng class 'active' cho content-ctn:
    const ordersContent = document.querySelector('.ORDERS.content-ctn');
    if (ordersContent && ordersContent.classList.contains('active')) {
        loadInitialOrders();
    }
    // Hoặc lắng nghe sự kiện click trên menu item 'ORDERS'
    const ordersMenuItem = document.querySelector('.menu-item.ORDERS');
    if (ordersMenuItem) {
        ordersMenuItem.addEventListener('click', () => {
            // Có thể cần một chút delay để đảm bảo content đã hiển thị
            setTimeout(loadInitialOrders, 50);
        });
    }
    // Lấy tất cả các modal của order
    const orderModals = document.querySelectorAll('.ORDERS .order-modal'); // Chỉ lấy modal trong phần order

    orderModals.forEach(modal => {
        modal.addEventListener('click', function (event) {
            // Kiểm tra xem nơi click có phải là chính cái nền modal không
            if (event.target === modal) {
                closeModal(modal); // Gọi hàm closeModal đã có
            }
        });
    });

};