function customerfunc() {
    let addCustomerForm = document.getElementById('customer-add-form');
    let addCustomerModal = document.getElementById('customer-add-modal');
    let openAddModalButton = document.getElementById('open-add-modal-button');
    let searchForm = document.getElementById('customer-search-form');
    let customerTableBody = document.querySelector('.customer-table tbody'); 
    let statusInfo = document.querySelector('.customer-status-info');
    const closeButtons = document.querySelectorAll('.customer-close-button');

    function createCustomerRowHtml(customer, canEdit, canDelete) {
        const safeUsername = customer.username.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safeName = customer.name.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safeEmail = customer.email.replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safePhone = (customer.phone || 'N/A').replace(/</g, "&lt;").replace(/>/g, "&gt;");
        const safeAddress = (customer.address || 'N/A').replace(/</g, "&lt;").replace(/>/g, "&gt;");

        return `
            <tr>
                <td>${customer.id}</td>
                <td>${safeUsername}</td>
                <td>${safeName}</td>
                <td>${safeEmail}</td>
                <td>${safePhone}</td>
                <td>${safeAddress}</td>
                <td>
                ${canEdit ? `<button class="customer-edit-button"
                            data-id="${customer.id}"
                            data-name="${safeName}"
                            data-email="${safeEmail}"
                            data-phone="${customer.phone || ''}"
                            data-address="${customer.address || ''}">
                        <i class="fas fa-edit"></i>
                    </button>`: ''}
                ${canDelete ? `<button class="customer-delete-button" data-id="${customer.id}" title="Delete Customer"> <i class="fas fa-trash"></i>
                    </button>`: ''}
                    
                    </td>
            </tr>
        `;
    }
    function updateCustomerTable(customers) {
        if (!customerTableBody) return;
        customerTableBody.innerHTML = '';
        Promise.all([checkPermission('EDIT_CUSTOMER'), checkPermission('DELETE_CUSTOMER')])
            .then(([canEdit, canDelete]) => {
                if (customers && customers.length > 0) {
                    let allRowsHtml = '';
                    customers.forEach(customer => {
                        allRowsHtml += createCustomerRowHtml(customer, canEdit, canDelete);
                    });
                    customerTableBody.innerHTML = allRowsHtml;
                    attachEditButtonListeners();
                } else {
                    customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">No customers found.</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error loading products:', error);
            });
    }

    function loadInitialCustomers() {
        if (!customerTableBody) return;
        if (statusInfo) statusInfo.textContent = 'Loading all customers...';
        customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Loading...</td></tr>`; 
        fetch('includes/right_content/customers/searchCustomers.php', {
            method: 'POST',
            body: new FormData(document.getElementById('customer-search-form'))
        })
            .then(response => response.text())
            .then(responseData => {
                try {
                    let result = JSON.parse(responseData);
                    if (result.status === 'success') {
                        updateCustomerTable(result.data); if (statusInfo) statusInfo.textContent = 'DISPLAYING CUSTOMER LIST (All Active)';
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
    if (!searchForm) {
        console.error('LỖI: Không tìm thấy form với ID "customer-search-form"');
    } else {
        searchForm.addEventListener('submit', function (event) {
            event.preventDefault(); const formData = new FormData(searchForm); 
            const searchUrl = 'includes/right_content/customers/searchCustomers.php';

            if (statusInfo) statusInfo.textContent = 'Searching...';
            fetch(searchUrl, {
                method: 'POST', body: formData
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let result = JSON.parse(responseData);
                        if (result.status === 'success') {
                            updateCustomerTable(result.data); let filterInfo = [];
                            for (let [key, value] of formData.entries()) {
                                if (value !== '') {
                                    let cleanKey = key.startsWith('search_') ? key.substring(7) : key;
                                    filterInfo.push(`${cleanKey}: ${value}`);
                                }
                            }
                            if (statusInfo) statusInfo.textContent = `DISPLAYING CUSTOMER LIST (Filtered by: ${filterInfo.join(', ') || 'All'})`;

                        } else {
                            console.error('Search Error from server:', result.message);
                            if (statusInfo) statusInfo.textContent = `Error searching: ${result.message}`;
                            if (customerTableBody) customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Error: ${result.message}</td></tr>`;
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    console.error('Fetch function error:', error);
                    if (statusInfo) statusInfo.textContent = `Error during search: ${error.message}`;
                    if (customerTableBody) customerTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Fetch Error: ${error.message}</td></tr>`;
                });
        });
    }
    function attachEditButtonListeners() {
        const editButtons = document.querySelectorAll('.customer-edit-button');
        const editModal = document.getElementById('customer-edit-modal');
        editButtons.forEach(button => {
            button.replaceWith(button.cloneNode(true));
        });
        document.querySelectorAll('.customer-edit-button').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id; const name = this.dataset.name;
                const email = this.dataset.email;
                const phone = this.dataset.phone;
                const address = this.dataset.address;
                document.getElementById('customer-edit-id').value = id;
                document.getElementById('customer-edit-name').value = name;
                document.getElementById('customer-edit-email').value = email;
                document.getElementById('customer-edit-phone').value = phone;
                document.getElementById('customer-edit-address').value = address;
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

                    const deleteUrl = 'includes/right_content/customers/deleteCustomer.php'; 
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
            }
        });
    }
    attachEditButtonListeners();
    function closeModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'none';
        }
    }
    function openModal(modalElement) {
        if (modalElement) {
            const form = modalElement.querySelector('form');
            if (form && form.id === 'customer-add-form') {
                form.reset();
            }
            modalElement.style.display = 'flex';
        }
    }
    if (openAddModalButton && addCustomerModal) {
        openAddModalButton.addEventListener('click', () => {
            addCustomerForm.reset(); openModal(addCustomerModal);
        });
    }
    closeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-modal-id');
            const modalToClose = document.getElementById(modalId);
            closeModal(modalToClose);
        });
    });
    if (addCustomerForm) {
        addCustomerForm.addEventListener('submit', function (event) {
            event.preventDefault(); const formData = new FormData(addCustomerForm);
            const actionUrl = 'includes/right_content/customers/addCustomer.php'; 
            const submitButton = addCustomerForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';
            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(responeData => {
                    try {
                        let data = JSON.parse(responeData);
                        if (data.status === 'success') {
                            closeModal(addCustomerModal); addCustomerForm.reset(); 
                            showMessageDialog(data.message, 'success');
                        } else if (data.status === 'validation_error') {
                            showMessageDialog(data.message, 'error');
                            if (data.errors && Array.isArray(data.errors)) {
                                console.error('Validation Errors:', data.errors);
                            }
                        }
                        else {
                            showMessageDialog(data.message || 'An unknown error occurred.', 'error');
                        }
                    }
                    catch {
                        console.error(responeData);
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    showMessageDialog(`An error occurred: ${error.message}`, 'error');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                });
        });
    }


    const editForm = document.getElementById('customer-edit-form'); if (editForm) {
        editForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(editForm);
            const customerId = formData.get('id');
            const updateUrl = 'includes/right_content/customers/updateCustomer.php';
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
                            if (data.updated_data && customerTableBody) {
                                const rowToUpdate = customerTableBody.querySelector(`button[data-id="${customerId}"]`)?.closest('tr');
                                if (rowToUpdate) {

                                    const cells = rowToUpdate.querySelectorAll('td');
                                    if (cells.length >= 6) {
                                        cells[2].textContent = data.updated_data.name;
                                        cells[3].textContent = data.updated_data.email;
                                        cells[4].textContent = data.updated_data.phone || 'N/A';
                                        cells[5].textContent = data.updated_data.address || 'N/A';
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
                                }
                            } else {
                                console.warn('Updated data not found in response or table body missing, table not updated.');
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
        });
    }
    const customersContent = document.querySelector('.CUSTOMERS.content-ctn');
    if (customersContent && customersContent.classList.contains('active')) {
        loadInitialCustomers();
    }
    const customersMenuItem = document.querySelector('.menu-item.CUSTOMERS'); if (customersMenuItem) {
        customersMenuItem.addEventListener('click', () => {
            setTimeout(() => {
                const isActive = customersContent && customersContent.classList.contains('active');
                const isEmpty = !customerTableBody || !customerTableBody.querySelector('tr') || customerTableBody.textContent.includes('Error') || customerTableBody.textContent.includes('Loading');

                if (isActive && isEmpty) {
                    loadInitialCustomers();
                } else if (isActive) {
                }
            }, 50);
        });
    }
    const customerModals = document.querySelectorAll('.CUSTOMERS .customer-modal');
    customerModals.forEach(modal => {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal(modal);
            }
        });
    });

};