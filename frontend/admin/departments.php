<style>
    .modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    overflow: auto;
}

.modal-content {
    background-color: #fff;
    margin: 50px auto;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: relative;
    min-height: 500px;
    display: flex;
    flex-direction: column;
}

.modal-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
    position: relative;
}

.modal-header h2 {
    font-size: 1.5em;
    color: #007bff;
    margin: 0;
    text-align: center;
    font-weight: bold;
}

.close-modal {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.5em;
    cursor: pointer;
    color: #666;
}

.modal-instruction {
    font-size: 0.9em;
    color: #666;
    margin-bottom: 15px;
    text-align: center;
}

.modal-body {
    flex-grow: 1;
    overflow-y: auto;
    padding-right: 10px;
}

.modal-body .form-group {
    margin-bottom: 20px;
}

.modal-body label {
    display: block;
    font-size: 0.9em;
    margin-bottom: 5px;
    color: #333;
    font-weight: 600;
}

.modal-body label i.fas {
    margin-left: 5px;
    color: #007bff;
}

.modal-body p {
    display: block;
    font-size: 0.8em;
    color: #666;
    margin-top: 5px; /* Added to place text below input */
}

.modal-body input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 0.9em;
    background-color: #f8f9fa;
}

.modal-body input[type="time"] {
    padding-left: 35px; /* Adjust for icon */
    position: relative;
}

.modal-body input[type="number"] {
    padding: 10px;
}

.modal-body input[placeholder="Enter ID"] {
    text-transform: uppercase;
}

.submit-modal-btn {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    margin-top: 20px;
}

.submit-modal-btn:hover {
    background-color: #0056b3;
}

#editDepartmentModal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    overflow: auto;
}

#editDepartmentModal .modal-content {
    background-color: #fff;
    margin: 50px auto;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

#editDepartmentModal .modal-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}

#editDepartmentModal .modal-header h2 {
    font-size: 1.5em;
    color: black;
    font-weight: bold;
}

#editDepartmentModal .close-modal {
    position: absolute;
    right: 10px;
    top: 5px;
    font-size: 1.5em;
    cursor: pointer;
    color: #666;
}

#editDepartmentModal .modal-instruction {
    font-size: 0.9em;
    color: #666;
    margin-bottom: 15px;
    text-align: center;
}

#editDepartmentModal .modal-body {
    overflow-y: auto;
    padding-right: 10px;
}

#editDepartmentModal .form-group {
    margin-bottom: 20px;
    position: relative;
}

#editDepartmentModal label {
    display: block;
    font-size: 0.9em;
    margin-bottom: 5px;
    color: #333;
    font-weight: 600;
}

#editDepartmentModal select,
#editDepartmentModal input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 0.9em;
    background-color: #f8f9fa;
}

#editDepartmentModal select {
    appearance: none; /* Remove default arrow in some browsers */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23007bff' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    padding-right: 30px; /* Space for custom arrow */
}

#editDepartmentModal p {
    display: block;
    font-size: 0.8em;
    color: #666;
    margin-top: 5px;
}

#editDepartmentModal .submit-modal-btn {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    margin-top: 20px;
}

#editDepartmentModal .submit-modal-btn:hover {
    background-color: #0056b3;
}

#editDepartmentModal .time-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #007bff;
    pointer-events: none;
}

#editDepartmentModal input[type="time"] {
    padding-left: 35px;
}

</style>

<section class="department-management">
    <h2 class="stat-icon-1"><i class="fas fa-building">  Department Management </i></h2>
    <p>Manage departments and their check-in/check-out time limit</p>
    <div class="controls-container">
        <div class="controls">
            <button class="add-btn"><i class="fas fa-plus"></i> Add New Department</button>
        </div>
    </div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th><span class="circle-icon" title="Select all"><i class="far fa-circle"></i></span></th>
                    <th>ID</th>
                    <th>Department Name</th>
                    <th>Check-in Limit</th>
                    <th>Check-out Limit</th>
                    <th>Workers</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="departments-table-body">
                <!-- Rows will be injected dynamically -->
            </tbody>
        </table>
        <p class="no-records" style="display: none;">No departments found for the selected criteria.</p>
    </div>
</section>

   <!-- Add New Department Modal -->
<div id="addDepartmentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Department</h2>
            <span class="close-modal">&times;</span>
        </div>
        <p class="modal-instruction">Create a new department with check-in and check-out limits.</p>
        <div class="modal-body">
           
            <div class="form-group">
                <label>Department Name *</label>
                <input type="text" placeholder="Enter department name">
            </div>
            <div class="form-group">
                <label>Check-in Limit *</label>
                <input type="time" placeholder="e.g., 09:00">
                <p>Latest time Workers can check in</p>
            </div>
            <div class="form-group">
                <label>Check-out Limit *</label>
                <input type="time" placeholder="e.g., 17:00">
                <p>Earliest time Workers can check out</p>
            </div>
           
            <button type="button" class="submit-modal-btn">Add Department</button>
        </div>
    </div>
</div>
<!-- Edit Department Modal -->
<div id="editDepartmentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Department</h2>
            <span class="close-modal">&times;</span>
        </div>
        <p class="modal-instruction">Edit the details of the selected department.</p>
        <div class="modal-body">
            <div class="form-group">
                <label>Department Name *</label>
                <input type="text" id="edit-name" placeholder="Enter department name">
            </div>
            <div class="form-group">
                <label>Check-in Limit *</label>
                <input type="time" id="edit-checkin" placeholder="e.g., 09:00">
                <p>Latest time Workers can check in</p>
            </div>
            <div class="form-group">
                <label>Check-out Limit *</label>
                <input type="time" id="edit-checkout" placeholder="e.g., 17:00">
                <p>Earliest time Workers can check out</p>
            </div>
            <button type="button" class="submit-modal-btn" id="save-changes-btn">Update Department</button>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get the modal and related elements
    const addBtn = document.querySelector('.add-btn');
    const addModal = document.getElementById('addDepartmentModal');
    const addCloseModal = addModal.querySelector('.close-modal');

    const editModal = document.getElementById('editDepartmentModal');
    const editCloseModal = editModal.querySelector('.close-modal');

    // Show the Add New Department modal
    addBtn.addEventListener('click', function() {
        addModal.style.display = 'block';
        // Add clock icons inside time inputs
        const timeInputs = addModal.querySelectorAll('input[type="time"]');
        timeInputs.forEach(input => {
            if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('time-icon')) {
                const icon = document.createElement('i');
                icon.classList.add('fas', 'fa-clock', 'time-icon');
                icon.style.position = 'absolute';
                icon.style.left = '10px';
                icon.style.top = '50%';
                icon.style.transform = 'translateY(-50%)';
                icon.style.color = '#007bff';
                icon.style.pointerEvents = 'none';
                input.parentElement.appendChild(icon);
                input.style.paddingLeft = '35px';
            }
        });
    });

    // ADD DEPARTMENT
    document.querySelector("#addDepartmentModal .submit-modal-btn")
    .addEventListener("click", function() {
        console.log("Add clicked");
        const name = addModal.querySelector("input[type='text']").value.trim();
        const checkin = addModal.querySelectorAll("input[type='time']")[0].value;
        const checkout = addModal.querySelectorAll("input[type='time']")[1].value;

        if (!name || !checkin || !checkout) {
            alert("Please fill all required fields.");
            return;
        }

        const data = {
            name: name,
            check_in_limit: checkin,
            check_out_limit: checkout,
            group_start_date: new Date().toISOString().slice(0,10) // adjust if needed
        };

        fetch("http://localhost/ttcl_proj/backend/api/department_api.php?action=create", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            console.log('Add Department response:', resp);
            if (resp.success) {
                alert("Department added successfully!");
                addModal.style.display = "none";
                loadDepartments();
            } else {
                alert("Failed to add: " + (resp.message || "unknown error"));
            }
        });
    });

    // Hide the Add New Department modal
    addCloseModal.addEventListener('click', function() {
        addModal.style.display = 'none';
        // Clean up icons when modal closes
        const icons = addModal.querySelectorAll('.time-icon');
        icons.forEach(icon => icon.remove());
    });

    // Delegate click event for edit buttons after table is loaded
    document.getElementById('departments-table-body').addEventListener('click', function(e) {
        const btn = e.target.closest('.action-btn.edit');
        if (btn) {
            // Find the row and extract department data
            const row = btn.closest('tr');
            const tds = row.querySelectorAll('td');
            const deptId = btn.dataset.id;
            const deptName = tds[2].textContent.trim();
            const checkin = tds[3].textContent.replace(/[^0-9:]/g, '').trim();
            const checkout = tds[4].textContent.replace(/[^0-9:]/g, '').trim();

            // Store the department id for editing
            editModal.dataset.editingId = deptId;

            editModal.querySelector('#edit-name').value = deptName;
            editModal.querySelector('#edit-checkin').value = checkin;
            editModal.querySelector('#edit-checkout').value = checkout;

            // Add clock icons inside time inputs
            const timeInputs = editModal.querySelectorAll('input[type="time"]');
            timeInputs.forEach(input => {
                if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('time-icon')) {
                    const icon = document.createElement('i');
                    icon.classList.add('fas', 'fa-clock', 'time-icon');
                    icon.style.position = 'absolute';
                    icon.style.left = '10px';
                    icon.style.top = '50%';
                    icon.style.transform = 'translateY(-50%)';
                    icon.style.color = '#007bff';
                    icon.style.pointerEvents = 'none';
                    input.parentElement.appendChild(icon);
                    input.style.paddingLeft = '35px';
                }
            });

            editModal.style.display = 'block';
        }
    });

    // EDIT DEPARTMENT
    document.querySelector("#save-changes-btn")
    .addEventListener("click", function() {
        console.log("Edit clicked");
        const id = editModal.dataset.editingId;
        const name = editModal.querySelector("#edit-name").value.trim();
        const checkin = editModal.querySelector("#edit-checkin").value;
        const checkout = editModal.querySelector("#edit-checkout").value;

        if (!name || !checkin || !checkout) {
            alert("Please fill all required fields.");
            return;
        }

        const data = {
            id: id,
            name: name,
            check_in_limit: checkin,
            check_out_limit: checkout,
            group_start_date: new Date().toISOString().slice(0,10) // adjust if needed
        };

        fetch("http://localhost/ttcl_proj/backend/api/department_api.php?action=update", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            console.log('Edit Department response:', resp);
            if (resp.success) {
                alert("Department updated successfully!");
                editModal.style.display = "none";
                loadDepartments();
            } else {
                alert("Failed to update: " + (resp.message || "unknown error"));
            }
        });
    });

    // Hide the Edit Department modal
    editCloseModal.addEventListener('click', function() {
        editModal.style.display = 'none';
        // Clean up icons when modal closes
        const icons = editModal.querySelectorAll('.time-icon');
        icons.forEach(icon => icon.remove());
    });

    // Hide modals if user clicks outside
    window.addEventListener('click', function(event) {
        if (event.target == addModal) {
            addModal.style.display = 'none';
            const icons = addModal.querySelectorAll('.time-icon');
            icons.forEach(icon => icon.remove());
        }
        if (event.target == editModal) {
            editModal.style.display = 'none';
            const icons = editModal.querySelectorAll('.time-icon');
            icons.forEach(icon => icon.remove());
        }
    });

    loadDepartments();

    function loadDepartments() {
        fetch("http://localhost/ttcl_proj/backend/api/department_api.php?action=getAll")
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById("departments-table-body");
                const noRecords = document.querySelector(".no-records");
                tbody.innerHTML = "";

                if (data.length === 0) {
                    noRecords.style.display = "block";
                    return;
                }

                noRecords.style.display = "none";

                data.forEach(dept => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td><span class="circle-icon"><i class="far fa-circle"></i></span></td>
                        <td>${dept.id}</td>
                        <td>${dept.name}</td>
                        <td><i class="fas fa-clock"></i> ${dept.check_in_limit}</td>
                        <td><i class="fas fa-clock"></i> ${dept.check_out_limit}</td>
                        <td>${dept.worker_count ?? 0} workers</td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn edit" data-id="${dept.id}"><i class="fas fa-edit"></i></button>
                                <button class="action-btn delete" data-id="${dept.id}" data-workers="${dept.worker_count ?? 0}"><i style="color: rgba(122, 0, 0, 1);" class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                
                document.querySelectorAll(".action-btn.delete").forEach(btn => {
                    btn.addEventListener("click", function() {
                        const deptId = this.dataset.id;
                        const workers = parseInt(this.dataset.workers || "0", 10);
                        if (workers > 0) {
                            alert("Cannot delete a department that has workers. Please remove all workers first.");
                            return;
                        }
                        if (confirm("Are you sure you want to delete this department?")) {
                            deleteDepartment(deptId);
                        }
                    });
                });
            })
            .catch(err => console.error("Error loading departments:", err));
    }

    function deleteDepartment(id) {
        fetch("http://localhost/ttcl_proj/backend/api/department_api.php?action=delete", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert("Department deleted successfully!");
                loadDepartments(); // Refresh the table
            } else {
                alert("Failed to delete department: " + (result.message || "Unknown error"));
            }
        })
        .catch(err => console.error("Delete error:", err));
    }
});
</script>
