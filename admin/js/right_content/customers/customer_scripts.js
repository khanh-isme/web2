function customerfunc() {
    let addCustomerForm = document.getElementById('customer-add-form');
    let addCustomerModal = document.getElementById('customer-add-modal');
    let openAddModalButton = document.getElementById('open-add-modal-button'); // Nút "ADD CUSTOMER"
    let searchForm = document.getElementById('customer-search-form'); // Form tìm kiếm
    let customerTableBody = document.querySelector('.customer-table tbody'); // Phần tbody của bảng
    let statusInfo = document.querySelector('.customer-status-info'); // Dòng thông tin trạng thái
    const closeButtons = document.querySelectorAll('.customer-close-button');

    function createCustomerRowHtml(customer, canEdit, canDelete) { // Thêm canLockUnlock
        const safeUsername = customer.username.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safeName = customer.name.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safeEmail = customer.email.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safePhone = (customer.phone || 'N/A').replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safeAddress = (customer.address || 'N/A').replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const status = customer.status || 'unknown'; // Lấy trạng thái từ dữ liệu customer

        let lockUnlockButtonHtml = '';
        if (canEdit) {
            const buttonText = status === 'active' ? 'Khóa <i class="fas fa-lock"></i>' : 'Mở <i class="fas fa-unlock"></i>';
            const buttonClass = status === 'active' ? 'customer-lock-button' : 'customer-unlock-button';
            lockUnlockButtonHtml = `<button class="${buttonClass}" data-id="${customer.id}" data-status="${status}" title="${status === 'active' ? 'Lock Customer' : 'Unlock Customer'}">${buttonText}</button>`;
        }

        return `
        <tr data-id="${customer.id}" data-status="${status}">
            <td>${customer.id}</td>
            <td>${safeUsername}</td>
            <td>${safeName}</td>
            <td>${safeEmail}</td>
            <td>${safePhone}</td>
            <td>${safeAddress}</td>
            <td class="customer-status-cell">${status.charAt(0).toUpperCase() + status.slice(1)}</td>
            <td>
                ${canEdit ? `<button class="customer-edit-button"
                            data-id="${customer.id}"
                            data-name="${safeName}"
                            data-email="${safeEmail}"
                            data-phone="${customer.phone || ''}"
                            data-address="${customer.address || ''}">
                        <i class="fas fa-edit"></i>
                    </button>
                    ` : ''}
                ${lockUnlockButtonHtml} 
                ${canDelete ? `<button class="customer-delete-button" data-id="${customer.id}" title="Delete Customer"> <i class="fas fa-trash"></i>
                    </button>` : ''}
            </td>
        </tr>
    `;
    }

    // --- Hàm cập nhật bảng với kết quả tìm kiếm ---
    function updateCustomerTable(customers) {
        if (!customerTableBody) return; // customerTableBody phải được định nghĩa ở đầu file hoặc đầu hàm cc()

        customerTableBody.innerHTML = ''; // Xóa các hàng hiện tại

        Promise.all([checkPermission('EDIT_CUSTOMER'), checkPermission('DELETE_CUSTOMER')])
            .then(([canEdit, canDelete]) => { // Nhận quyền mới
                if (customers && customers.length > 0) {
                    let allRowsHtml = '';
                    customers.forEach(customer => {
                        // Truyền trạng thái và quyền vào createCustomerRowHtml
                        allRowsHtml += createCustomerRowHtml(customer, canEdit, canDelete);
                    });
                    customerTableBody.innerHTML = allRowsHtml;
                    attachEditButtonListeners();
                    // attachDeleteButtonListeners(); // Nếu bạn có hàm này
                    attachLockUnlockButtonListeners(); // Gắn listener cho nút Khóa/Mở
                } else {
                    customerTableBody.innerHTML = `<tr><td colspan="8" style="text-align: center;">No customers found.</td></tr>`; // Tăng colspan
                }
            })
            .catch(error => {
                console.error('Error checking permissions or updating table:', error);
                customerTableBody.innerHTML = `<tr><td colspan="8" style="text-align: center;">Error loading data.</td></tr>`;
            });
    }

    function loadInitialCustomers() {
        if (!customerTableBody) return; // Thoát nếu không tìm thấy table body

        if (statusInfo) statusInfo.textContent = 'Loading all customers...';
        customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Loading...</td></tr>`; // Hiển thị loading

        // Gọi backend searchCustomers.php với POST rỗng để lấy tất cả
        fetch('includes/right_content/customers/searchCustomers.php', { //
            method: 'POST',
            body: new FormData(document.getElementById('customer-search-form'))
            // Không cần body hoặc dùng new FormData() rỗng
        })
            .then(response => response.text())
            .then(responseData => {
                try {
                    let result = JSON.parse(responseData);
                    if (result.status === 'success') {
                        updateCustomerTable(result.data); // Cập nhật bảng
                        if (statusInfo) statusInfo.textContent = 'DISPLAYING CUSTOMER LIST (All Active)';
                    } else {
                        console.error('Error loading initial customers:', result.message);
                        if (statusInfo) statusInfo.textContent = `Error: ${result.message}`;
                        customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Error: ${result.message}</td></tr>`;
                    }
                }
                catch {
                    console.error(responseData);
                }
            })
            .catch(error => {
                console.error('Fetch error loading initial customers:', error);
                if (statusInfo) statusInfo.textContent = `Error: ${error.message}`;
                customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Could not load customers. ${error.message}</td></tr>`;
            });
    }

    // --- Xử lý sự kiện Submit Form Tìm Kiếm ---
    if (!searchForm) {
        console.error('LỖI: Không tìm thấy form với ID "customer-search-form"');
    } else {

        // --- Xử lý sự kiện Submit Form Tìm Kiếm ---
        searchForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(searchForm);
            const searchUrl = 'includes/right_content/customers/searchCustomers.php';

            if (statusInfo) statusInfo.textContent = 'Searching...';

            fetch(searchUrl, { method: 'POST', body: formData })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let result = JSON.parse(responseData);
                        if (result.status === 'success') {
                            updateCustomerTable(result.data); // Cập nhật bảng

                            // Cập nhật trạng thái rõ ràng hơn (lấy các giá trị không rỗng từ formData)
                            let filterInfo = [];
                            for (let [key, value] of formData.entries()) {
                                if (value !== '') {
                                    // Bỏ tiền tố 'search_' cho đẹp hơn (tùy chọn)
                                    let cleanKey = key.startsWith('search_') ? key.substring(7) : key;
                                    filterInfo.push(`${cleanKey}: ${value}`);
                                }
                            }
                            if (statusInfo) statusInfo.textContent = `DISPLAYING CUSTOMER LIST (Filtered by: ${filterInfo.join(', ') || 'All'})`;

                        } else {
                            // Hiển thị lỗi từ server
                            console.error('Search Error from server:', result.message);
                            if (statusInfo) statusInfo.textContent = `Error searching: ${result.message}`;
                            if (customerTableBody) customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Error: ${result.message}</td></tr>`; // colspan="6"
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    // Xử lý lỗi fetch hoặc lỗi parse JSON
                    console.error('Fetch function error:', error);
                    if (statusInfo) statusInfo.textContent = `Error during search: ${error.message}`;
                    if (customerTableBody) customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Fetch Error: ${error.message}</td></tr>`; // colspan="6"
                });
        });
    }

    function attachLockUnlockButtonListeners() {
        const lockUnlockButtons = document.querySelectorAll('.customer-lock-button, .customer-unlock-button');
        lockUnlockButtons.forEach(button => {
            // Xóa listener cũ để tránh gắn nhiều lần
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);

            newButton.addEventListener('click', function () {
                const customerId = this.dataset.id;
                const currentStatus = this.dataset.status;
                const actionText = currentStatus === 'active' ? 'khóa' : 'mở khóa';

                if (confirm(`Bạn có chắc muốn ${actionText} khách hàng với ID: ${customerId}?`)) {
                    const formData = new FormData();
                    formData.append('customer_id', customerId);
                    formData.append('current_status', currentStatus);

                    fetch('includes/right_content/customers/toggleCustomerStatus.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.text()) // Chuyển sang response.text() để debug
                        .then(responseData => {
                            try {
                                const data = JSON.parse(responseData);
                                if (data.status === 'success') {
                                    showMessageDialog(data.message, 'success');
                                    // Cập nhật UI:
                                    const row = document.querySelector(`.customer-table tbody tr[data-id="${customerId}"]`);
                                    if (row) {
                                        row.dataset.status = data.new_status; // Cập nhật data-status của <tr>
                                        const statusCell = row.querySelector('.customer-status-cell');
                                        if (statusCell) {
                                            statusCell.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                                        }
                                        // Cập nhật nút
                                        this.dataset.status = data.new_status;
                                        if (data.new_status === 'active') {
                                            this.classList.remove('customer-unlock-button');
                                            this.classList.add('customer-lock-button');
                                            this.innerHTML = 'Khóa <i class="fas fa-lock"></i>';
                                            this.title = 'Lock Customer';
                                        } else {
                                            this.classList.remove('customer-lock-button');
                                            this.classList.add('customer-unlock-button');
                                            this.innerHTML = 'Mở <i class="fas fa-unlock"></i>';
                                            this.title = 'Unlock Customer';
                                        }
                                    }
                                } else {
                                    showMessageDialog(data.message || 'Failed to update customer status.', 'error');
                                }
                            } catch (e) {
                                console.error("Error parsing JSON:", e);
                                console.error("Response data:", responseData); // Log dữ liệu trả về nếu không phải JSON
                                showMessageDialog('Lỗi xử lý phản hồi từ server.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error toggling customer status:', error);
                            showMessageDialog(`Error: ${error.message}`, 'error');
                        });
                }
            });
        });
    }

    // --- Hàm để gán lại sự kiện cho nút Edit (CẦN GỌI SAU KHI CẬP NHẬT BẢNG) ---
    function attachEditButtonListeners() {
        const editButtons = document.querySelectorAll('.customer-edit-button');
        const editModal = document.getElementById('customer-edit-modal'); // Đảm bảo đã lấy modal edit

        editButtons.forEach(button => {
            // Quan trọng: Xóa listener cũ trước khi thêm mới để tránh gọi nhiều lần
            button.replaceWith(button.cloneNode(true));
        });

        // Thêm listener mới cho các nút (kể cả nút vừa clone)
        document.querySelectorAll('.customer-edit-button').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id; // Sử dụng dataset thay vì getAttribute
                const name = this.dataset.name;
                const email = this.dataset.email;
                const phone = this.dataset.phone;
                const address = this.dataset.address;

                // Điền dữ liệu vào form sửa
                document.getElementById('customer-edit-id').value = id;
                document.getElementById('customer-edit-name').value = name;
                document.getElementById('customer-edit-email').value = email;
                document.getElementById('customer-edit-phone').value = phone;
                document.getElementById('customer-edit-address').value = address;

                // Mở modal sửa (đảm bảo hàm openModal tồn tại)
                openModal(editModal);
            }
            );

        });
    }

    if (customerTableBody) {
        customerTableBody.addEventListener('click', function (event) {
            const deleteButton = event.target.closest('.customer-delete-button');
            if (deleteButton) {

                const customerId = deleteButton.dataset.id;
                const customerRow = deleteButton.closest('tr');

                if (!customerId || !customerRow) {
                    console.error("Could not get customer ID or table row for deletion.");
                    return;
                }

                if (confirm(`Are you sure you want to mark customer with ID: ${customerId} as deleted?`)) {

                    const deleteUrl = 'includes/right_content/customers/deleteCustomer.php'; // Đảm bảo đường dẫn đúng
                    const formData = new FormData();
                    formData.append('customer_id', customerId);

                    fetch(deleteUrl, {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.text())
                        .then(responseData => {
                            try {
                                let data = JSON.parse(responseData);

                                if (data.status === 'success') {
                                    customerRow.remove();

                                    showMessageDialog(data.message, 'success');
                                } else {
                                    showMessageDialog(data.message || 'Failed to mark customer as deleted.', 'error');
                                }
                            }
                            catch {
                                console.error(responseData);
                            }
                        })
                        .catch(error => {
                            console.error('Error during soft delete fetch:', error);
                            showMessageDialog(`Error: ${error.message}`, 'error');
                        });
                } else {

                }
            } // Kết thúc if(deleteButton)
        }); // Kết thúc listener click cho delete/edit trong tbody
    }

    // Gọi hàm này một lần khi trang tải xong để gán sự kiện cho các nút ban đầu
    attachEditButtonListeners();

    // --- Hàm đóng Modal ---
    function closeModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'none';
        }
    }

    // --- Hàm mở Modal ---
    function openModal(modalElement) {
        if (modalElement) {
            const form = modalElement.querySelector('form');
            if (form && form.id === 'customer-add-form') {
                form.reset();
            }
            // --- THAY ĐỔI CHÍNH Ở ĐÂY ---
            // Đặt display thành 'flex' để hiển thị và kích hoạt căn giữa Flexbox
            modalElement.style.display = 'flex';
            // ------------------------
        }
    }

    // --- Xử lý sự kiện mở Modal Thêm Khách Hàng ---
    if (openAddModalButton && addCustomerModal) {
        openAddModalButton.addEventListener('click', () => {
            addCustomerForm.reset(); // Xóa dữ liệu cũ trong form khi mở
            // Xóa các thông báo lỗi cũ (nếu có)
            // clearErrorMessages(addCustomerForm);
            openModal(addCustomerModal);
        });
    }

    // --- Xử lý sự kiện nút đóng Modal ---
    closeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-modal-id');
            const modalToClose = document.getElementById(modalId);
            closeModal(modalToClose);
        });
    });

    // --- Xử lý sự kiện Submit Form Thêm Khách Hàng bằng Fetch ---
    if (addCustomerForm) {
        addCustomerForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Ngăn chặn hành vi submit mặc định của form

            // Thu thập dữ liệu từ form
            const formData = new FormData(addCustomerForm);

            // Lấy URL xử lý từ thuộc tính action của form (hoặc hardcode)
            // const actionUrl = addCustomerForm.action; // Lấy từ action="#" sẽ không đúng
            const actionUrl = 'includes/right_content/customers/addCustomer.php'; // Đường dẫn tới file PHP

            // Hiển thị trạng thái đang xử lý (ví dụ: thay đổi text nút submit)
            const submitButton = addCustomerForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';

            // Xóa các thông báo lỗi cũ (nếu có)
            // clearErrorMessages(addCustomerForm);

            // Gửi dữ liệu bằng Fetch API
            fetch(actionUrl, {
                method: 'POST',
                body: formData // FormData tự động đặt Content-Type phù hợp (multipart/form-data)
            })
                .then(response => response.text())
                .then(responeData => {
                    try {
                        let data = JSON.parse(responeData);
                        // Xử lý dữ liệu JSON trả về từ server
                        if (data.status === 'success') {
                            // Thành công
                            closeModal(addCustomerModal); // Đóng modal
                            addCustomerForm.reset();      // Xóa form
                            showMessageDialog(data.message, 'success'); // Hiển thị thông báo thành công
                            // Tùy chọn: Tải lại trang để xem khách hàng mới
                            // location.reload();

                            // Tùy chọn nâng cao: Thêm khách hàng mới vào bảng mà không cần tải lại trang
                            // addNewCustomerRowToTable(data.new_customer_id, formData.get('name'), ...);

                        } else if (data.status === 'validation_error') {
                            // Có lỗi xác thực từ server
                            showMessageDialog(data.message, 'error');
                            // Hiển thị lỗi cụ thể (nếu có)
                            if (data.errors && Array.isArray(data.errors)) {
                                // Ví dụ: hiển thị lỗi dưới các trường input tương ứng
                                // displayValidationErrors(addCustomerForm, data.errors);
                                console.error('Validation Errors:', data.errors);
                            }
                        }
                        else {
                            // Các lỗi khác từ server (ví dụ: lỗi database)
                            showMessageDialog(data.message || 'An unknown error occurred.', 'error');
                        }
                    }
                    catch {
                        console.error(responeData);
                    }
                })
                .catch(error => {
                    // Xử lý lỗi mạng hoặc lỗi phân tích JSON
                    console.error('Fetch Error:', error);
                    showMessageDialog(`An error occurred: ${error.message}`, 'error');
                })
                .finally(() => {
                    // Luôn thực hiện sau khi fetch hoàn thành (dù thành công hay lỗi)
                    // Khôi phục trạng thái nút submit
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                });
        });
    }


    const editForm = document.getElementById('customer-edit-form'); // Lấy form sửa
    if (editForm) {
        editForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Ngăn gửi form truyền thống


            const formData = new FormData(editForm);
            const customerId = formData.get('id'); // Lấy ID từ hidden input

            const updateUrl = 'includes/right_content/customers/updateCustomer.php'; // URL file PHP xử lý cập nhật

            const submitButton = editForm.querySelector('.customer-save-button');
            const originalButtonText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';

            fetch(updateUrl, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let data = JSON.parse(responseData);
                        if (data.status === 'success') {
                            const editModal = document.getElementById('customer-edit-modal');
                            closeModal(editModal);
                            showMessageDialog(data.message, 'success');

                            // Cập nhật hàng trong bảng
                            if (data.updated_data && customerTableBody) { // Thêm kiểm tra customerTableBody
                                const rowToUpdate = customerTableBody.querySelector(`button[data-id="${customerId}"]`)?.closest('tr');
                                if (rowToUpdate) {

                                    const cells = rowToUpdate.querySelectorAll('td');
                                    if (cells.length >= 6) { // ID, Name, Email, Phone, Address, Actions
                                        cells[2].textContent = data.updated_data.name;
                                        cells[3].textContent = data.updated_data.email;
                                        cells[4].textContent = data.updated_data.phone || 'N/A';
                                        cells[5].textContent = data.updated_data.address || 'N/A';
                                        // Cập nhật data attributes cho nút edit
                                        const editBtn = rowToUpdate.querySelector('.customer-edit-button');
                                        if (editBtn) {
                                            editBtn.dataset.name = data.updated_data.name;
                                            editBtn.dataset.email = data.updated_data.email;
                                            editBtn.dataset.phone = data.updated_data.phone;
                                            editBtn.dataset.address = data.updated_data.address;
                                        }
                                    }
                                } else {
                                    console.warn('Could not find row in table to update for ID:', customerId);
                                    // Cân nhắc reload lại nếu không tìm thấy hàng: location.reload();
                                }
                            } else {
                                console.warn('Updated data not found in response or table body missing, table not updated.');
                                // Cân nhắc reload lại nếu cần cập nhật: location.reload();
                            }

                        } else if (data.status === 'info') {
                            const editModal = document.getElementById('customer-edit-modal');
                            closeModal(editModal);
                            showMessageDialog(data.message, 'info');
                        }
                        else {
                            showMessageDialog(data.message || 'Failed to update customer.', 'error');
                            if (data.errors) { console.error('Validation Errors:', data.errors); }
                        }
                    }
                    catch {
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    console.error('Error updating customer:', error);
                    showMessageDialog(`Error: ${error.message}`, 'error');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                });
        }); // <<< Kết thúc addEventListener của editForm
    }
    const customersContent = document.querySelector('.CUSTOMERS.content-ctn');
    if (customersContent && customersContent.classList.contains('active')) {
        loadInitialCustomers();
    }

    // Lắng nghe sự kiện click menu item 'CUSTOMERS' để load lại nếu cần
    // (Cần đảm bảo class 'menu-item' và 'CUSTOMERS' đúng với HTML của bạn)
    const customersMenuItem = document.querySelector('.menu-item.CUSTOMERS'); //
    if (customersMenuItem) {
        customersMenuItem.addEventListener('click', () => {
            // Chờ một chút để đảm bảo tab content đã được active bởi script khác
            setTimeout(() => {
                // Chỉ load lại nếu tab content thực sự active và bảng đang trống hoặc có thông báo lỗi/loading
                const isActive = customersContent && customersContent.classList.contains('active');
                const isEmpty = !customerTableBody || !customerTableBody.querySelector('tr') || customerTableBody.textContent.includes('Error') || customerTableBody.textContent.includes('Loading');

                if (isActive && isEmpty) {
                    loadInitialCustomers();
                } else if (isActive) {

                    // Hoặc bạn có thể luôn load lại nếu muốn: loadInitialCustomers();
                }
            }, 50); // Delay nhỏ
        });
    }
    // --- Các hàm khác (ví dụ: mở/đóng modal, xóa lỗi, thêm hàng vào bảng...) ---
    // Lấy tất cả các modal của customer
    const customerModals = document.querySelectorAll('.CUSTOMERS .customer-modal'); // Chỉ lấy modal trong phần customer

    customerModals.forEach(modal => {
        modal.addEventListener('click', function (event) {
            // Kiểm tra xem nơi click có phải là chính cái nền modal không
            if (event.target === modal) {
                closeModal(modal); // Gọi hàm closeModal đã có
            }
        });
    });
    // function clearErrorMessages(form) { /* ... */ }
    // function displayValidationErrors(form, errors) { /* ... */ }
    // function addNewCustomerRowToTable(id, name, email, phone, address) { /* ... */ }

}; // Kết thúc DOMContentLoaded