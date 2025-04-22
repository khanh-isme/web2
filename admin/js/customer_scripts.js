// --- Tệp: customer_scripts.js (hoặc tên tương tự) ---

function cc() {
    // --- Lấy các phần tử DOM ---
    let addCustomerForm = document.getElementById('customer-add-form');
    let addCustomerModal = document.getElementById('customer-add-modal');
    let openAddModalButton = document.getElementById('open-add-modal-button'); // Nút "ADD CUSTOMER"
    let searchForm = document.getElementById('customer-search-form'); // Form tìm kiếm
    //let searchInput = document.getElementById('customer-keyword'); // Ô input từ khóa
    let customerTableBody = document.querySelector('.customer-table tbody'); // Phần tbody của bảng
    let statusInfo = document.querySelector('.customer-status-info'); // Dòng thông tin trạng thái

    // (Thêm các phần tử khác nếu cần: modal chỉnh sửa, nút đóng, bảng...)
    const closeButtons = document.querySelectorAll('.customer-close-button');

    // --- Hàm tiện ích để hiển thị thông báo ---
    // (Bạn có thể thay thế bằng thư viện thông báo đẹp hơn như SweetAlert, Toastify)
    function showMessage(message, type = 'info') {
        // Ví dụ đơn giản dùng alert
        alert(`[${type.toUpperCase()}] ${message}`);
        // Hoặc hiển thị trong một div cụ thể trên trang
        // const messageArea = document.getElementById('message-area');
        // if (messageArea) {
        //     messageArea.textContent = message;
        //     messageArea.className = `message ${type}`; // Thêm class để style
        //     messageArea.style.display = 'block';
        // }
    }

    // --- Hàm để tạo HTML cho một hàng khách hàng ---
    function createCustomerRowHtml(customer) {
        // Sử dụng htmlspecialchars tương đương trong JS hoặc đảm bảo dữ liệu an toàn
        const safeName = customer.name.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safeEmail = customer.email.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safePhone = (customer.phone || 'N/A').replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safeAddress = (customer.address || 'N/A').replace(/</g, "&lt;").replace(/>/g, "&gt;");

        return `
            <tr>
                <td>${customer.id}</td>
                <td>${safeName}</td>
                <td>${safeEmail}</td>
                <td>${safePhone}</td>
                <td>${safeAddress}</td>
                <td>
                    <button class="customer-edit-button"
                            data-id="${customer.id}"
                            data-name="${safeName}"
                            data-email="${safeEmail}"
                            data-phone="${customer.phone || ''}"
                            data-address="${customer.address || ''}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="customer-delete-button" data-id="${customer.id}" title="Delete Customer"> <i class="fas fa-trash"></i>
                    </button>
                    </td>
            </tr>
        `;
    }
    
    // --- Hàm cập nhật bảng với kết quả tìm kiếm ---
    function updateCustomerTable(customers) {
        if (!customerTableBody) return; // customerTableBody phải được định nghĩa ở đầu file hoặc đầu hàm cc()
    
        customerTableBody.innerHTML = ''; // Xóa các hàng hiện tại
    
        if (customers && customers.length > 0) {
            let allRowsHtml = '';
            customers.forEach(customer => {
                allRowsHtml += createCustomerRowHtml(customer);
            });
            customerTableBody.innerHTML = allRowsHtml;
            attachEditButtonListeners(); // Quan trọng: Gắn lại listener cho nút Sửa
            // attachDeleteButtonListeners(); // Sẽ thêm hàm này ở Bước 4
        } else {
            // Hiển thị thông báo không tìm thấy kết quả
            customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">No customers found.</td></tr>`; // Colspan=6
        }
    }

    // --- Xử lý sự kiện Submit Form Tìm Kiếm ---
    if (!searchForm) {
        console.error('LỖI: Không tìm thấy form với ID "customer-search-form"');
    } else {
         console.log('Đã tìm thấy form tìm kiếm:', searchForm); // Xác nhận đã tìm thấy
         // --- Xử lý sự kiện Submit Form Tìm Kiếm ---
         searchForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Giữ nguyên: Ngăn form gửi theo cách truyền thống
            console.log('--- Search Form Submitted ---'); // Giữ lại để kiểm tra
       
            // --- BỎ CÁC DÒNG LIÊN QUAN ĐẾN keyword và searchInput cũ ---
            // const keyword = searchInput.value.trim(); // BỎ DÒNG NÀY
            // console.log('Keyword:', keyword);         // BỎ DÒNG NÀY
       
            // --- LẤY DỮ LIỆU BẰNG FormData ---
            const formData = new FormData(searchForm); // Lấy tất cả input trong form
       
            // Log dữ liệu để kiểm tra (tùy chọn)
            // const searchDataForLog = Object.fromEntries(formData.entries());
            // console.log('Search Data:', searchDataForLog);
       
            // --- SỬA LẠI URL VÀ CẤU HÌNH FETCH ---
            const searchUrl = 'includes/searchCustomers.php'; // Chỉ cần đường dẫn tới file PHP
            console.log('Attempting to fetch URL (POST):', searchUrl);
       
            if (statusInfo) statusInfo.textContent = 'Searching...';
       
            // Gọi API tìm kiếm bằng Fetch, dùng POST
            fetch(searchUrl, {
                method: 'POST',   // <<< Đổi thành POST
                body: formData    // <<< Gửi FormData trong body
            })
            .then(response => {
                console.log('Fetch response status:', response.status);
                if (!response.ok) {
                     // Xử lý lỗi HTTP tốt hơn
                    return response.text().then(text => {
                        console.error('Server responded with error:', text);
                        throw new Error(`HTTP error! status: ${response.status}, Response: ${text}`);
                    });
                }
                 // Kiểm tra content type trước khi parse JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Nếu không phải JSON, log và báo lỗi
                    return response.text().then(text => {
                        console.error('Response was not JSON:', text);
                        throw new Error('Received non-JSON response from server.');
                    });
                }
            })
            .then(result => {
                 console.log('Parsed fetch result:', result); // Log kết quả JSON
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
            })
            .catch(error => {
                // Xử lý lỗi fetch hoặc lỗi parse JSON
                console.error('Fetch function error:', error);
                if (statusInfo) statusInfo.textContent = `Error during search: ${error.message}`;
                if (customerTableBody) customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Fetch Error: ${error.message}</td></tr>`; // colspan="6"
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
             button.addEventListener('click', function() {
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
        customerTableBody.addEventListener('click', function(event) {
           const deleteButton = event.target.closest('.customer-delete-button');
           if (deleteButton) {
               console.log('Delete button clicked (delegated)');
               const customerId = deleteButton.dataset.id;
               const customerRow = deleteButton.closest('tr');

               if (!customerId || !customerRow) {
                   console.error("Could not get customer ID or table row for deletion.");
                   return;
               }

               if (confirm(`Are you sure you want to mark customer with ID: ${customerId} as deleted?`)) {
                   console.log(`Proceeding with soft delete for customer ID: ${customerId}`);
                   const deleteUrl = 'includes/deleteCustomer.php'; // Đảm bảo đường dẫn đúng
                   const formData = new FormData();
                   formData.append('customer_id', customerId);

                   fetch(deleteUrl, {
                       method: 'POST',
                       body: formData
                   })
                   .then(response => {
                       console.log('Soft delete response status:', response.status);
                       if (!response.ok) { return response.text().then(text => { throw new Error(`Server error: ${response.status} - ${text}`) }); }
                       const contentType = response.headers.get('content-type');
                       if (contentType && contentType.includes('application/json')) { return response.json(); }
                       else { return response.text().then(text => { throw new Error('Received non-JSON response from delete endpoint.') }); }
                   })
                   .then(data => {
                       console.log('Parsed soft delete result:', data);
                       if (data.status === 'success') {
                           customerRow.remove();
                           console.log('Customer row removed from table view.');
                           showMessage(data.message, 'success');
                       } else {
                           showMessage(data.message || 'Failed to mark customer as deleted.', 'error');
                       }
                   })
                   .catch(error => {
                       console.error('Error during soft delete fetch:', error);
                       showMessage(`Error: ${error.message}`, 'error');
                   });
               } else {
                   console.log('Soft delete cancelled by user.');
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
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-id');
            const modalToClose = document.getElementById(modalId);
            closeModal(modalToClose);
        });
    });

    // --- Xử lý sự kiện Submit Form Thêm Khách Hàng bằng Fetch ---
    if (addCustomerForm) {
        addCustomerForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn chặn hành vi submit mặc định của form

            // Thu thập dữ liệu từ form
            const formData = new FormData(addCustomerForm);

            // Lấy URL xử lý từ thuộc tính action của form (hoặc hardcode)
            // const actionUrl = addCustomerForm.action; // Lấy từ action="#" sẽ không đúng
            const actionUrl = 'includes/addCustomer.php'; // Đường dẫn tới file PHP

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
            .then(response => {
                // Kiểm tra xem phản hồi có phải JSON không
                const contentType = response.headers.get('content-type');
                if (response.ok && contentType && contentType.includes('application/json')) {
                    return response.json(); // Phân tích JSON nếu hợp lệ
                }
                // Nếu không phải JSON hoặc có lỗi server (status code không phải 2xx)
                return response.text().then(text => {
                    throw new Error(`Server error: ${response.status} ${response.statusText}. Response: ${text}`);
                });
            })
            .then(data => {
                // Xử lý dữ liệu JSON trả về từ server
                if (data.status === 'success') {
                    // Thành công
                    closeModal(addCustomerModal); // Đóng modal
                    addCustomerForm.reset();      // Xóa form
                    showMessage(data.message, 'success'); // Hiển thị thông báo thành công

                    // Tùy chọn: Tải lại trang để xem khách hàng mới
                    // location.reload();

                    // Tùy chọn nâng cao: Thêm khách hàng mới vào bảng mà không cần tải lại trang
                    // addNewCustomerRowToTable(data.new_customer_id, formData.get('name'), ...);

                } else if (data.status === 'validation_error') {
                    // Có lỗi xác thực từ server
                    showMessage(data.message, 'error');
                    // Hiển thị lỗi cụ thể (nếu có)
                    if (data.errors && Array.isArray(data.errors)) {
                       // Ví dụ: hiển thị lỗi dưới các trường input tương ứng
                       // displayValidationErrors(addCustomerForm, data.errors);
                       console.error('Validation Errors:', data.errors);
                    }
                }
                 else {
                    // Các lỗi khác từ server (ví dụ: lỗi database)
                    showMessage(data.message || 'An unknown error occurred.', 'error');
                }
            })
            .catch(error => {
                // Xử lý lỗi mạng hoặc lỗi phân tích JSON
                console.error('Fetch Error:', error);
                showMessage(`An error occurred: ${error.message}`, 'error');
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
    if (!editForm) {
        console.error('LỖI: Không tìm thấy form với ID "customer-edit-form"');
    } else {
        console.log('Đã tìm thấy form chỉnh sửa:', editForm);
        editForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn gửi form truyền thống
            console.log('--- Edit Customer Form Submitted ---');

            const formData = new FormData(editForm);
            const customerId = formData.get('id'); // Lấy ID từ hidden input

            const updateUrl = 'includes/updateCustomer.php'; // URL file PHP xử lý cập nhật

            const submitButton = editForm.querySelector('.customer-save-button');
            const originalButtonText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';

            fetch(updateUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Edit response status:', response.status);
                 if (!response.ok) {
                    return response.text().then(text => { throw new Error(`Server error: ${response.status} - ${text}`) });
                }
                 const contentType = response.headers.get('content-type');
                 if (contentType && contentType.includes('application/json')) {
                     return response.json();
                 } else {
                     return response.text().then(text => { throw new Error('Received non-JSON response from update endpoint.') });
                 }
            })
            .then(data => {
                 console.log('Parsed update result:', data);
                if (data.status === 'success') {
                    const editModal = document.getElementById('customer-edit-modal');
                    closeModal(editModal);
                    showMessage(data.message, 'success');

                    // Cập nhật hàng trong bảng
                    if (data.updated_data && customerTableBody) { // Thêm kiểm tra customerTableBody
                        const rowToUpdate = customerTableBody.querySelector(`button[data-id="${customerId}"]`)?.closest('tr');
                        if (rowToUpdate) {
                             console.log('Updating table row for ID:', customerId);
                             const cells = rowToUpdate.querySelectorAll('td');
                             if (cells.length >= 6) { // ID, Name, Email, Phone, Address, Actions
                                cells[1].textContent = data.updated_data.name;
                                cells[2].textContent = data.updated_data.email;
                                cells[3].textContent = data.updated_data.phone || 'N/A';
                                cells[4].textContent = data.updated_data.address || 'N/A';
                                // Cập nhật data attributes cho nút edit
                                const editBtn = rowToUpdate.querySelector('.customer-edit-button');
                                 if(editBtn) {
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
                     showMessage(data.message, 'info');
                }
                else {
                    showMessage(data.message || 'Failed to update customer.', 'error');
                    if (data.errors) { console.error('Validation Errors:', data.errors); }
                }
            })
            .catch(error => {
                console.error('Error updating customer:', error);
                showMessage(`Error: ${error.message}`, 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            });
        }); // <<< Kết thúc addEventListener của editForm
    }
    // --- Các hàm khác (ví dụ: mở/đóng modal, xóa lỗi, thêm hàng vào bảng...) ---
    // function clearErrorMessages(form) { /* ... */ }
    // function displayValidationErrors(form, errors) { /* ... */ }
    // function addNewCustomerRowToTable(id, name, email, phone, address) { /* ... */ }

}; // Kết thúc DOMContentLoaded