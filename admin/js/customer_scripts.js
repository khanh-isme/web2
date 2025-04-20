// --- Tệp: customer_scripts.js (hoặc tên tương tự) ---

 function cc() {
console.log('Customer script loaded'); // Kiểm tra xem script đã được tải thành công
    // --- Lấy các phần tử DOM ---
    let addCustomerForm = document.getElementById('customer-add-form');
    let addCustomerModal = document.getElementById('customer-add-modal');
    let openAddModalButton = document.getElementById('open-add-modal-button'); // Nút "ADD CUSTOMER"
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

    // --- Hàm đóng Modal ---
    function closeModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'none';
        }
    }

     // --- Hàm mở Modal ---
     function openModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'block';
            console.log('dan');
        }
    }

    // --- Xử lý sự kiện mở Modal Thêm Khách Hàng ---
    if (openAddModalButton && addCustomerModal) {
        console.log('Add Customer Modal and Button found'); // Kiểm tra sự kiện click
        openAddModalButton.addEventListener('click', () => {
            console.log('Open Add Customer Modal'); // Kiểm tra sự kiện click
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

    // --- Các hàm khác (ví dụ: mở/đóng modal, xóa lỗi, thêm hàng vào bảng...) ---
    // function clearErrorMessages(form) { /* ... */ }
    // function displayValidationErrors(form, errors) { /* ... */ }
    // function addNewCustomerRowToTable(id, name, email, phone, address) { /* ... */ }

}; // Kết thúc DOMContentLoaded