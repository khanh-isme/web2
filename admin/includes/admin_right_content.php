<div id="right_content">
    <div class="STATS content-ctn">
    STATS
    </div>
    <div class="PRODUCTS content-ctn">
        
    </div>
    <div class="ORDERS content-ctn">
    ORDERS
    </div>
    <div class="CUSTOMERS content-ctn">
    <div class="customer-content">
    <div class="customer-header">
        <h1>Customers</h1>
        <p class="customer-help-text">View, add, edit customer information. <a href="#">Need help?</a></p>
        <div class="customer-header-buttons">
            <button class="customer-add-button" id="open-add-modal-button">ADD CUSTOMER</button>
        </div>
    </div>

    <form method="GET" action="#">
        <div class="customer-search-section">
            <div class="customer-search-title">SEARCH CUSTOMERS</div>
            <div class="customer-search-inputs">
                <div class="customer-search-row">
                    <div class="customer-filter-group">
                        <input type="text" name="keyword" id="customer-keyword" placeholder="Enter name, email, phone..." class="customer-search-bar">
                    </div>
                    <button type="submit" class="customer-search-button">SEARCH</button>
                </div>
            </div>
        </div>
    </form>

    <p class="customer-status-info">DISPLAYING CUSTOMER LIST</p>

    <div class="table-scroll">
        <table class="customer-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-customers"></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" class="customer-checkbox"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button class="customer-edit-button" data-id="1"> <i class="fas fa-edit"></i>
                        </button>
                         </td>
                </tr>
                
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