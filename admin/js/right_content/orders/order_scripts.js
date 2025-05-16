function orderfunc() {    let orderSearchForm = document.getElementById('order-search-form');
    let orderTableBody = document.querySelector('.order-table tbody');    
    let orderStatusInfo = document.querySelector('.order-status-info');    
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
                <td>${totalAmount}</td>
                <td>${status}</td>
                <td>${shippingName}</td>
                <td>${shippingPhone}</td>
                <td>${shippingAddress}</td>
                <td style="text-align: center;">${actionButtons}</td>
            </tr>
        `;
    }    function updateOrderTable(orders) {
        if (!orderTableBody) {
            console.error("Lỗi: Không tìm thấy tbody của bảng order.");
            return;
        }        orderTableBody.innerHTML = '';

        checkPermission('MANAGE_ORDER_STATUS')
            .then(canEdit => {
                try {
                    if (orders && orders.length > 0) {
                        let allRowsHtml = '';
                        orders.forEach(order => {
                            allRowsHtml += createOrderRowHtml(order, canEdit);
                        });
                        orderTableBody.innerHTML = allRowsHtml;                        
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
    }    if (orderSearchForm) {
        orderSearchForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(orderSearchForm);
            const searchUrl = 'includes/right_content/orders/searchOrders.php';
            if (orderStatusInfo) {
                orderStatusInfo.textContent = 'Searching orders...';
            }
            fetch(searchUrl, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let result = JSON.parse(responseData);
                        if (result.status === 'success') {
                            updateOrderTable(result.data);                            
                            if (orderStatusInfo) {
                                let filterInfo = [];                                
                                for (let [key, value] of formData.entries()) {
                                    if (value !== '') {
                                        let cleanKey = key.startsWith('search_') ? key.substring(7).replace(/_/g, ' ') : key.replace(/_/g, ' ');                                        
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
                                orderTableBody.innerHTML = `<tr><td colspan="9" style="text-align: center;">Error: ${result.message}</td></tr>`;                            
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
                        orderTableBody.innerHTML = `<tr><td colspan="9" style="text-align: center;">Fetch Error: ${error.message}</td></tr>`;                    
                    }
                })
                .finally(() => {                });
        });        loadInitialOrders();
    } else {
        console.error("Lỗi: Không tìm thấy form với ID 'order-search-form'");
    }    function loadInitialOrders() {
        if (orderStatusInfo) orderStatusInfo.textContent = 'Loading orders...';
        fetch('includes/right_content/orders/searchOrders.php', { method: 'POST' })            
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
    }    function attachOrderActionListeners() {
        const viewButtons = orderTableBody.querySelectorAll('.order-view-button');
        const editButtons = orderTableBody.querySelectorAll('.order-edit-button');

        viewButtons.forEach(button => {            button.replaceWith(button.cloneNode(true));
        });        orderTableBody.querySelectorAll('.order-view-button').forEach(button => {
            button.addEventListener('click', handleViewOrder);
        });


        editButtons.forEach(button => {
            button.replaceWith(button.cloneNode(true));
        });
        orderTableBody.querySelectorAll('.order-edit-button').forEach(button => {
            button.addEventListener('click', handleEditOrder);
        });
    }    function handleViewOrder(event) {
        const button = event.currentTarget;        
        const orderId = button.dataset.id;        
        const viewModal = document.getElementById('order-view-modal');
        if (!viewModal) return;        
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
                const imageUrl = item.product_image || 'imgs/placeholder.png';                
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
        }        const row = button.closest('tr');
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
            console.warn("Could not find table row to prefill modal.");        }        
            const itemsContainer = document.getElementById('view-order-items');
        if (!itemsContainer) return;
        itemsContainer.innerHTML = '<p><em>Loading order items...</em></p>';        
        fetch(`includes/right_content/orders/getOrderDetails.php?id=${orderId}`)            
        .then(response => response.text())
            .then(responseData => {
                try {
                    let result = JSON.parse(responseData);
                    if (result.status === 'success') {                        
                        itemsContainer.innerHTML = createOrderItemsHtml(result.data);
                    } else {                        
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
                openModal(viewModal);
            });
    }

    function capitalizeFirstLetter(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }    function handleEditOrder(event) {
        const button = event.currentTarget;
        const orderId = button.dataset.id;        
        const editModal = document.getElementById('order-edit-modal');
        if (!editModal) return;        
        const row = button.closest('tr');
        let currentStatus = 'N/A';
        if (row) {
            const statusCell = row.cells[4];            
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
        }        document.getElementById('edit-order-id').value = orderId;
        document.getElementById('display-edit-order-id').textContent = orderId;
        document.getElementById('display-current-status').textContent = currentStatus;        
        if (statusSelect) {
            statusSelect.value = currentStatus;
        }        openModal(editModal);    
    }    
        const orderEditForm = document.getElementById('order-edit-form');
    if (orderEditForm) {
        orderEditForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(orderEditForm);
            const orderId = formData.get('order_id');
            const newStatus = formData.get('status');            
            closeModal(document.getElementById('order-edit-modal'));
            showMessageDialog('<p><i class="fa-regular fa-circle-check green icon"></i>Order status updated successfully (simulated)!</p>');            const rowToUpdate = orderTableBody.querySelector(`.order-edit-button[data-id="${orderId}"]`)?.closest('tr');
            if (rowToUpdate) {
                const statusCell = rowToUpdate.cells[4];
                if (statusCell) {
                    statusCell.textContent = newStatus;
                }
            }        });
    }    function openModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'flex';        
        }
    }    function closeModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'none';
        }
    }    const closeButtons = document.querySelectorAll('.ORDERS .order-close-button');    
    closeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-modal-id');
            const modalToClose = document.getElementById(modalId);
            closeModal(modalToClose);
        });
    });    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') return unsafe;        
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }    const ordersContent = document.querySelector('.ORDERS.content-ctn');
    if (ordersContent && ordersContent.classList.contains('active')) {
        loadInitialOrders();
    }    const ordersMenuItem = document.querySelector('.menu-item.ORDERS');
    if (ordersMenuItem) {
        ordersMenuItem.addEventListener('click', () => {            
            setTimeout(loadInitialOrders, 50);
        });
    }    const orderModals = document.querySelectorAll('.ORDERS .order-modal');
    orderModals.forEach(modal => {
        modal.addEventListener('click', function (event) {            
            if (event.target === modal) {
                closeModal(modal);            
            }
        });
    });

};