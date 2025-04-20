document.addEventListener('DOMContentLoaded', function() {
    // --- Modal Handling ---
    const openAddModalButton = document.getElementById('open-add-modal-button');
    const addModal = document.getElementById('customer-add-modal');
    const editModal = document.getElementById('customer-edit-modal');
    const closeButtons = document.querySelectorAll('.customer-close-button');
    const editButtons = document.querySelectorAll('.customer-edit-button');

    // Function to open a modal
    function openModal(modal) {
        if (modal) {
            modal.style.display = 'block'; // Show the modal
        }
    }

    // Function to close a modal
    function closeModal(modal) {
        if (modal) {
            modal.style.display = 'none'; // Hide the modal
        }
    }

    // Event listener for opening the Add modal
    if (openAddModalButton) {
        openAddModalButton.addEventListener('click', () => {
            openModal(addModal);
        });
    }

    // Event listeners for close buttons
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-id');
            const modalToClose = document.getElementById(modalId);
            closeModal(modalToClose);
        });
    });

    // Close modal if clicking outside the modal content
    window.addEventListener('click', function(event) {
        if (event.target === addModal) {
            closeModal(addModal);
        }
        if (event.target === editModal) {
            closeModal(editModal);
        }
    });

    // --- Edit Form Population ---
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get data from the button's data attributes
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const address = this.getAttribute('data-address');

            // Populate the edit form fields
            document.getElementById('customer-edit-id').value = id;
            document.getElementById('customer-edit-name').value = name;
            document.getElementById('customer-edit-email').value = email;
            document.getElementById('customer-edit-phone').value = phone;
            document.getElementById('customer-edit-address').value = address;

            // Open the edit modal
            openModal(editModal);
        });
    });

     // --- Select All Checkbox ---
    const selectAllCheckbox = document.getElementById('select-all-customers');
    const customerCheckboxes = document.querySelectorAll('.customer-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            customerCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Optional: Uncheck "select all" if any individual checkbox is unchecked
    customerCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            } else {
                // Check if all are checked
                let allChecked = true;
                customerCheckboxes.forEach(cb => {
                    if (!cb.checked) {
                        allChecked = false;
                    }
                });
                selectAllCheckbox.checked = allChecked;
            }
        });
    });
});