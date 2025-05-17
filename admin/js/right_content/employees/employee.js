function Employee() {
    const editModal = document.getElementById("employee-edit-modal");
    const editCloseBtn = editModal ? editModal.querySelector(".employee-close-button") : null;
    const editForm = document.getElementById("employee-edit-form");

    if (editCloseBtn && editModal) {
        editCloseBtn.addEventListener("click", function () {
            editModal.style.display = "none";
            if (editForm) editForm.reset();
        });
    }

    window.addEventListener("click", function (event) {
        if (event.target === editModal) {
            editModal.style.display = "none";
            if (editForm) editForm.reset();
        }
    });
    const openBtn = document.getElementById("open-add-employee-modal-button");
    const addModal = document.getElementById("employee-add-modal");
    const closeBtn = addModal ? addModal.querySelector(".employee-close-button") : null;

    const addForm = document.getElementById("employee-add-form");

    if (openBtn && addModal) {
        openBtn.addEventListener("click", function () {
            addModal.style.display = "flex";
        });
    }

    if (closeBtn && addModal) {
        closeBtn.addEventListener("click", function () {
            addModal.style.display = "none";
            if (addForm) addForm.reset();
        });
    }

    window.addEventListener("click", function (event) {
        if (event.target === addModal) {
            addModal.style.display = "none";
            if (addForm) addForm.reset();
        }
    });

    document.addEventListener("DOMContentLoaded", Employee);
    function loadAdminList() {
        fetch('includes/right_content/employees/get_admins.php')
            .then(response => response.text())
            .then(responseData => {
                try {
                    let data = JSON.parse(responseData);
                    const tbody = document.querySelector("#abc");
                    tbody.innerHTML = "";

                    Promise.all([checkPermission('EDIT_EMPLOYEE'), checkPermission('DELETE_EMPLOYEE')])
                        .then(([canEdit, canDelete]) => {
                            data.forEach(admin => {
                                const tr = document.createElement("tr");
                            
                                tr.innerHTML = `
                            <td>${admin.stt}</td>
                            <td>${admin.username}</td>
                            <td>${admin.fullname}</td>
                            <td>${admin.status}</td>
                            <td>${admin.role || ""}</td>
                            <td>
                                ${!admin.isCurrentAdmin? `
                                ${canEdit ? `<button class="employee-edit-button" title="Sửa" onclick="editAdmin(${admin.id})">&#9998;</button>
                                <button class="employee-edit-button" title="Quyền" onclick="showPermissionModal(${admin.id})">&#9881;</button>` : ''}
                                ${canDelete ? `<button class="employee-delete-button" title="Xóa" onclick="deleteAdmin(${admin.id})">&#128465;</button>` : ''}
                                ` : 'This is your account' }
                            </td>
                        `;
                                tbody.appendChild(tr);
                            });
                        });
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(error => {
                console.error("Lỗi khi lấy danh sách admin:", error);
            });
    }

    loadAdminList();



    window.editAdmin = function (id) {
        document.getElementById("employee-edit-modal").style.display = "flex";
        fetch(`includes/right_content/employees/editEmployee.php?id=${id}`)
            .then(response => response.text())
            .then(responseData => {
                try {
                    let data = JSON.parse(responseData);
                    if (data.success && data.employee) {
                        const editForm = document.getElementById("employee-edit-form");
                        editForm.querySelector("#employee-edit-id").value = data.employee.id;
                        editForm.querySelector("#employee-edit-username").value = data.employee.username;
                        editForm.querySelector("#employee-edit-fullname").value = data.employee.fullname;
                        editForm.querySelector("#employee-edit-role").value = data.employee.role;
                        editForm.querySelector("#employee-edit-status").value = data.employee.status;
                        editForm.querySelector("#employee-edit-password").value = ""; 


                    } else {
                        alert(data.message || "Không tìm thấy nhân viên!");
                    }
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(error => {
                alert("Lỗi khi lấy thông tin nhân viên!");
                console.error(error);
            });
    }

    if (editForm) {
        editForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(editForm);
            fetch("includes/right_content/employees/updateEmployee.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let data = JSON.parse(responseData);
                        if (data.success) {
                            alert("Cập nhật nhân viên thành công!");
                            editForm.reset();
                            editModal.style.display = "none";
                            loadAdminList();
                        } else {
                            alert(data.message || "Cập nhật nhân viên thất bại!");
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    alert("Có lỗi xảy ra khi cập nhật nhân viên!");
                    console.error(error);
                });
        });
    }

    window.deleteAdmin = function (id) {
        if (confirm("Bạn có chắc chắn muốn xóa nhân viên này?")) {
            fetch(`includes/right_content/employees/deleteEmployee.php?id=${id}`)
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let result = JSON.parse(responseData);
                        alert(result.message);
                        loadAdminList();
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
                .catch(error => {
                    alert("Có lỗi xảy ra khi xóa nhân viên!");
                    console.error('Lỗi khi xóa nhân viên:', error);
                });
        }
    };

    if (addForm)
        addForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(addForm);

            fetch("includes/right_content/employees/addEmployee.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(responseData => {
                    try {
                        let data = JSON.parse(responseData);
                        if (data.success) {
                            alert("Thêm nhân viên thành công!");
                            addForm.reset();
                            addModal.style.display = "none";
                            loadAdminList();
                        } else {
                            if(data.message==='username_duplicated')
                            {
                                document.getElementById("error-message").innerText = 'Tên đăng nhập đã được sử dụng!'
                            }
                            else
                            {
                                document.getElementById("error-message").innerText = '';
                                alert(data.message || "Thêm nhân viên thất bại!");
                            }
                        }
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                })
        });

    window.showPermissionModal = function (adminId) {
        document.getElementById('employee-permission-modal').style.display = 'flex';
        document.getElementById('permission-employee-id').value = adminId;

        fetch('includes/right_content/employees/getPer.php?admin_id=' + adminId)
            .then(res => res.text())
            .then(responseData => {
                try
                {
                    let data=JSON.parse(responseData);
                    const container = document.getElementById('permission-list');
                    container.innerHTML = '';
    
                    if (data.permissions && Array.isArray(data.permissions)) {
                        data.permissions.forEach(permission => {
                            const wrapper = document.createElement('div');
                            wrapper.style.marginBottom = '10px';
    
                            const label = document.createElement('label');
                            label.style.cursor = 'pointer';
    
                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.name = 'permissions[]';
                            checkbox.value = permission.id;
                            checkbox.checked = permission.hasPermission;
    
                            label.appendChild(checkbox);
                            label.appendChild(document.createTextNode(' ' + permission.name));
    
                            if (permission.source === 'role') {
                                const tag = document.createElement('small');
                                tag.textContent = '(role)'
                                tag.style.marginLeft = '5px';
                                tag.style.fontSize = '12px';
                                tag.style.color = 'gray';
                                label.appendChild(tag);
                            }
    
                            wrapper.appendChild(label);
                            container.appendChild(wrapper);
                        });
                    } else {
                        container.innerHTML = '<p>Không có quyền nào.</p>';
                    }
                }
                catch(error)
                {
                    console.error(error);
                    console.error(responseData);
                }
            });
    }

    closePermissionModal = document.getElementById("close-permission-modal");
    if (closePermissionModal) {
        closePermissionModal.onclick = function () {
            document.getElementById("employee-permission-modal").style.display = "none";
        };
    }

    window.addEventListener("click", function (event) {
        const modal = document.getElementById("employee-permission-modal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
    document.getElementById("save-permission-button").onclick = function () {
        const employeeId = document.getElementById("permission-employee-id").value;
        const permissionList = document.getElementById("permission-list");

        const checkedBoxes = permissionList.querySelectorAll('input[type="checkbox"]:checked');
        const permissions = Array.from(checkedBoxes).map(cb => cb.value);

        fetch('includes/right_content/employees/updateEmployeePermissions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: employeeId,
                permissions: permissions
            })
        })
            .then(response => response.text())
            .then(responseData => {
                try
                {
                    let data=JSON.parse(responseData);
                    if (data.success) {
                        alert('Cập nhật quyền thành công!');
                        document.getElementById("employee-permission-modal").style.display = "none";
                    } else {
                        alert(data.message || 'Cập nhật quyền thất bại!');
                    }
                }
                catch(error)
                {
                    console.error(error);
                    console.error(responseData);
                }
            })
            .catch(() => {
                alert('Có lỗi khi cập nhật quyền!');
            });
    };

    window.showRestoreModal = function () {
        const modal = document.getElementById("employee-restore-modal");
        const tbody = document.getElementById("restore-employee-tbody");
        if (!modal || !tbody) return;
        tbody.innerHTML = '<tr><td colspan="3">Đang tải...</td></tr>';

        fetch('includes/right_content/employees/getDeletedEmployees.php')
            .then(res => res.text())
            .then(responseData => {
                try {
                    let data = JSON.parse(responseData);
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3">Không có nhân viên nào bị xóa.</td></tr>';
                    } else {
                        data.forEach(emp => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                            <td>${emp.stt}</td>
                            <td>${emp.username}</td>
                            <td>
                                <button class="restore-btn" onclick="restoreEmployee(${emp.id})" title="Khôi phục ">&#10084;</button>
                            </td>
                        `;
                            tbody.appendChild(tr);
                        });
                    }
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            });
        modal.style.display = "flex";
    };

    window.restoreEmployee = function (id) {
        if (confirm("Khôi phục nhân viên này?")) {
            fetch(`includes/right_content/employees/restoreEmployee.php?id=${id}`)
                .then(res => res.text())
                .then(responseData => {
                    try {
                        let data = JSON.parse(responseData);
                        alert(data.message);
                        showRestoreModal();
                        loadAdminList();
                    }
                    catch (error) {
                        console.error(error);
                        console.error(responseData);
                    }
                });
        }
    };
    if (document.getElementById("restore-employee-button"))
        document.getElementById("restore-employee-button").onclick = function () {
            window.showRestoreModal();
        };

    if (document.getElementById("close-restore-modal"))
        document.getElementById("close-restore-modal").onclick = function () {
            document.getElementById("employee-restore-modal").style.display = "none";
        };
    window.addEventListener("click", function (event) {
        const modal = document.getElementById("employee-restore-modal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
    window.searchEmployees = function () {
        const username = document.getElementById("employee_search_username").value;
        const fullname = document.getElementById("search_fullname").value;
        const status = document.getElementById("search_status1").value;
        const role = document.getElementById("search_role").value;

        fetch('includes/right_content/employees/searchEmployees.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                username: username,
                fullname: fullname,
                status: status,
                role: role
            })
        })
            .then(res => res.text())
            .then(responseData => {
                try {
                    let data = JSON.parse(responseData);
                    const tbody = document.querySelector("#abc");
                    tbody.innerHTML = "";
                    Promise.all([checkPermission('EDIT_EMPLOYEE'), checkPermission('DELETE_EMPLOYEE')])
                        .then(([canEdit, canDelete]) => {
                            if (data.employees.length === 0) {
                                tbody.innerHTML = '<tr><td colspan="6">Không tìm thấy nhân viên phù hợp.</td></tr>';
                            } else {
                                data.employees.forEach(admin => {
                                    const tr = document.createElement("tr");
                                    tr.innerHTML = `
                            <td>${admin.stt}</td>
                            <td>${admin.username}</td>
                            <td>${admin.fullname}</td>
                            <td>${admin.status}</td>
                            <td>${admin.role || ""}</td>
                            <td>
                                ${canEdit ? `<button class="employee-edit-button" title="Sửa" onclick="editAdmin(${admin.id})">&#9998;</button>
                                <button class="employee-edit-button" title="Quyền" onclick="showPermissionModal(${admin.id})">&#9881;</button>` : ''}
                                ${canDelete ? `<button class="employee-delete-button" title="Xóa" onclick="deleteAdmin(${admin.id})">&#128465;</button>` : ''}
                            </td>
                        `;
                                    tbody.appendChild(tr);
                                });
                            }
                        });
                }
                catch (error) {
                    console.error(error);
                    console.error(responseData);
                }
            });
    }

    document.getElementById("employee-search-form").addEventListener("submit", function (e) {
        e.preventDefault();
        searchEmployees();
    });
    document.getElementById("search-button").onclick = function () {
        searchEmployees();
    }
    fetch('includes/right_content/employees/getRoles.php')
        .then(res => res.text())
        .then(responseData => {
            try {
                let roles = JSON.parse(responseData);
                const Add = document.getElementById('employee-add-role');
                const Edit = document.getElementById('employee-edit-role');
                Add.innerHTML = '<option value="">-- Chọn role --</option>';
                roles.forEach(roleName => {
                    const option = document.createElement('option');
                    option.value = roleName;
                    option.textContent = roleName;
                    Add.appendChild(option);
                });
                Edit.innerHTML = '<option value="">-- Chọn role --</option>';
                roles.forEach(roleName => {
                    const option = document.createElement('option');
                    option.value = roleName;
                    option.textContent = roleName;
                    Edit.appendChild(option);
                });
                const searchRole = document.getElementById('search_role');
                searchRole.innerHTML = '<option value="">All</option>';
                roles.forEach(roleName => {
                    const option = document.createElement('option');
                    option.value = roleName;
                    option.textContent = roleName;
                    searchRole.appendChild(option);
                });
            }
            catch (error) {
                console.error(error);
                console.error(responseData);
            }
        });
}