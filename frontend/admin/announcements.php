<style>
    /* Add New Announcement Modal Styles  */
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
    min-height: 550px;
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
    color: black;
    margin: 0;
    text-align: center;
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
    margin-bottom: 15px;
}

.modal-body .row {
    display: flex;
    gap: 15px;
}

.modal-body .col {
    flex: 1;
}

.modal-body label {
    display: block;
    font-size: 0.9em;
    margin-bottom: 5px;
    color: #333;
}

.modal-body input, .modal-body select, .modal-body textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 0.9em;
    background-color: #f8f9fa;
}

.modal-body textarea {
    height: 100px;
    resize: vertical;
}

.modal-body input[type="date"] {
    padding: 10px;
}

.modal-body input[placeholder="Enter ID"] {
    text-transform: uppercase; /* Convert ID to uppercase */
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
    margin-top: 15px;
}

.submit-modal-btn:hover {
    background-color: #0056b3;
}

#editAnnouncementModal {
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

#editAnnouncementModal .modal-content {
    background-color: #fff;
    margin: 50px auto;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

#editAnnouncementModal .modal-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}

#editAnnouncementModal .modal-header h2 {
    font-size: 1.5em;
    color: black;
    font-weight: bold;
}

#editAnnouncementModal .close-modal {
    position: absolute;
    right: 10px;
    top: 5px;
    font-size: 1.5em;
    cursor: pointer;
    color: #666;
}

#editAnnouncementModal .modal-instruction {
    font-size: 0.9em;
    color: #666;
    margin-bottom: 15px;
    text-align: center;
}

#editAnnouncementModal .modal-body {
    overflow-y: auto;
    padding-right: 10px;
}

#editAnnouncementModal .form-group {
    margin-bottom: 20px;
}

#editAnnouncementModal label {
    display: block;
    font-size: 0.9em;
    margin-bottom: 5px;
    color: #333;
    font-weight: 600;
}

#editAnnouncementModal select,
#editAnnouncementModal input,
#editAnnouncementModal textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 0.9em;
    background-color: #f8f9fa;
}

#editAnnouncementModal textarea {
    height: 100px;
    resize: vertical;
}

#editAnnouncementModal p {
    display: block;
    font-size: 0.8em;
    color: #666;
    margin-top: 5px;
}

#editAnnouncementModal .submit-modal-btn {
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

#editAnnouncementModal .submit-modal-btn:hover {
    background-color: #0056b3;
}
</style>

<section class="announcements-management">
    <h2 class="stat-icon-1"><i class="fas fa-bullhorn"></i> Announcements Management</h2>
    <p>Create and manage announcements for field workers by department</p>

    <div class="controls-container">
        <div class="announcements-controls">
            <div class="filter-section">
                <select id="announcement-department">
                    <option value="all">All Departments</option>
                </select>
                <input type="date" id="announcement-date">
            </div>
        </div>
        <div class="controls">
            <button type="button" id="delete-selected">Delete Selected</button>
            <button class="add-btn">
                <i class="fas fa-plus"></i> Add New Announcement
            </button>
        </div>
    </div>

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>ID</th>
                    <th>Department</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Display Date</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="announcement-table-body"></tbody>
        </table>
    </div>
</section>

    <!-- Add New Announcement Modal -->
    <div id="addAnnouncementModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Announcement</h2>
                <span class="close-modal">&times;</span>
            </div>
            <p class="modal-instruction">Create a new announcement for field workers.</p>
            <div class="modal-body">
                
                <form id="addForm">
                    <div class="form-group">
                        <label>Department *</label>
                        <select id="addDepartment" name="department_id" required>
                            <option value="">Loading...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" id="add-title" placeholder="Enter announcement title">
                    </div>
                    <div class="form-group">
                        <label>Content *</label>
                        <textarea id="add-content" placeholder="Enter announcement content"></textarea>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label>Display Date</label>
                            <input type="date" id="add-display">
                        </div>
                    </div>
                    <button type="submit" class="submit-modal-btn" id="submit-add-btn">Add Announcement</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div id="editAnnouncementModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Announcement</h2>
                <span class="close-modal">&times;</span>
            </div>
            <p class="modal-instruction">Edit the details of the selected announcement.</p>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit-id"> <!-- store announcement ID -->
                    <div class="form-group">
                        <label>Department *</label>
                        <select id="edit-dept" name="department_id" required>
                            <option value="">-- Select Department --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" id="edit-title">
                    </div>
                    <div class="form-group">
                        <label>Display Date *</label>
                        <input type="date" id="edit-display">
                    </div>
                    <div class="form-group">
                        <label>Content *</label>
                        <textarea id="edit-content"></textarea>
                    </div>
                    <button type="submit" class="submit-modal-btn" id="save-changes-btn">Update Announcement</button>
                </form>
            </div>
        </div>
    </div>
    
<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById("announcement-department");
    const dateInput = document.getElementById("announcement-date");
    const tableBody = document.getElementById("announcement-table-body");
    const addBtn = document.querySelector('.add-btn');
    const addModal = document.getElementById('addAnnouncementModal');
    const addCloseModal = addModal.querySelector('.close-modal');
    const editModal = document.getElementById('editAnnouncementModal');
    const editCloseModal = editModal.querySelector('.close-modal');

    // Show Add New Announcement modal
    addBtn.addEventListener('click', function() {
        addModal.style.display = 'block';
    });
    // Hide Add New Announcement modal
    addCloseModal.addEventListener('click', function() {
        addModal.style.display = 'none';
    });
    // Hide Edit Announcement modal
    editCloseModal.addEventListener('click', function() {
        editModal.style.display = 'none';
    });
    // Hide modals if user clicks outside
    window.addEventListener('click', function(event) {
        if (event.target == addModal) {
            addModal.style.display = 'none';
        }
        if (event.target == editModal) {
            editModal.style.display = 'none';
        }
    });

    // Create Clear Filters button dynamically
    const clearBtn = document.createElement("button");
    clearBtn.textContent = "Clear Filters";
    clearBtn.type = "button";
    clearBtn.style.marginLeft = "10px";
    clearBtn.addEventListener("click", function () {
        departmentSelect.value = "all";
        dateInput.value = "";
        loadAnnouncements();
    });
    document.querySelector(".filter-section").appendChild(clearBtn);

    // Fetch and populate departments dynamically
    function loadDepartments() {
        fetch("../../backend/api/department_api.php?action=list")
            .then(res => res.json())
            .then(data => {
                // Populate filter dropdown
                departmentSelect.innerHTML = '<option value="all">All Departments</option>';
                data.forEach(dept => {
                    const opt = document.createElement("option");
                    opt.value = dept.id;
                    opt.textContent = dept.name;
                    departmentSelect.appendChild(opt);
                });

                // Populate add form dropdown
                const addDeptSelect = document.getElementById("addDepartment");
                if (addDeptSelect) {
                    addDeptSelect.innerHTML = '<option value="all">All Departments</option>';
                    data.forEach(dept => {
                        const opt = document.createElement("option");
                        opt.value = dept.id;
                        opt.textContent = dept.name;
                        addDeptSelect.appendChild(opt);
                    });
                }

                // Populate edit form dropdown
                const editDeptSelect = document.getElementById("edit-dept");
                if (editDeptSelect) {
                    editDeptSelect.innerHTML = '<option value="all">All Departments</option>';
                    data.forEach(dept => {
                        const opt = document.createElement("option");
                        opt.value = dept.id;
                        opt.textContent = dept.name;
                        editDeptSelect.appendChild(opt);
                    });
                }
            });
    }

    // Fetch announcements with filters
    function loadAnnouncements() {
        const department_id = departmentSelect.value !== "all" ? departmentSelect.value : "";
        const display_date = dateInput.value ? dateInput.value : "";
        const url = `../../backend/api/announcement_api.php?action=list&department_id=${department_id}&display_date=${display_date}`;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = "";
                if (data.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan='8'>No announcements found</td></tr>";
                    return;
                }
                data.forEach(item => {
                    let row = `
                        <tr>
                            <td><input type="checkbox" class="select-row" data-id="${item.id}"></td>
                            <td>${item.id}</td>
                            <td>${item.department_name}</td>
                            <td>${item.title}</td>
                            <td>${item.message}</td>
                            <td>${item.display_date}</td>
                            <td>${item.created_at}</td>
                            <td>
                                <button class="action-btn edit" data-dept="${item.department_name}" data-content="${item.message}" data-display="${item.display_date}"><i class="fas fa-edit"></i></button>
                                <button class="action-btn delete" data-id="${item.id}"><i style="color: rgba(122, 0, 0, 1);" class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML("beforeend", row);
                });
            });
    }

    // Event delegation for edit and delete buttons
    tableBody.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.action-btn.edit');
        if (editBtn) {
            const dept = editBtn.getAttribute('data-dept');
            const content = editBtn.getAttribute('data-content');
            const display = editBtn.getAttribute('data-display');
            const row = editBtn.closest('tr');
            const id = row.children[1].textContent;
            const title = row.children[3].textContent;

            editModal.querySelector('#edit-id').value = id;
            // Set department after options are loaded
            loadDepartments();
            setTimeout(() => {
                editModal.querySelector('#edit-dept').value = dept;
            }, 200);
            editModal.querySelector('#edit-title').value = title;
            editModal.querySelector('#edit-content').value = content;
            editModal.querySelector('#edit-display').value = display;

            editModal.style.display = 'block';
            return;
        }
        const delBtn = e.target.closest('.action-btn.delete');
        if (delBtn) {
            const id = delBtn.getAttribute('data-id');
            if (!confirm("Are you sure you want to delete this announcement?")) return;
            fetch(`../../backend/api/announcement_api.php?action=delete&id=${id}`)
                .then(res => res.json())
                .then(resp => {
                    if (resp.success) {
                        alert("Deleted successfully!");
                        loadAnnouncements();
                    } else {
                        alert("Failed to delete");
                    }
                });
        }
    });

    // Select all checkboxes
    document.getElementById("selectAll").addEventListener("change", function() {
        const checkboxes = document.querySelectorAll(".select-row");
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Hook filters
    departmentSelect.addEventListener("change", loadAnnouncements);
    dateInput.addEventListener("change", loadAnnouncements);

    // Initial load
    loadDepartments();
    loadAnnouncements();



    // --- ADD ANNOUNCEMENT ---
    document.getElementById("addForm").addEventListener("submit", function(e) {
        e.preventDefault();
    let department_id = document.getElementById("addDepartment").value;
    if (department_id === "all") department_id = 0;
        const data = {
            title: document.getElementById("add-title").value.trim(),
            message: document.getElementById("add-content").value.trim(),
            department_id: department_id,
            display_date: document.getElementById("add-display").value
        };
        fetch("../../backend/api/announcement_api.php?action=create", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                alert("Announcement added successfully!");
                document.getElementById("addAnnouncementModal").style.display = "none";
                loadAnnouncements();
            } else {
                alert("Failed to add announcement.");
            }
        });
    });

    // --- EDIT ANNOUNCEMENT ---
    document.getElementById("editForm").addEventListener("submit", function(e) {
        e.preventDefault();
    let department_id = document.getElementById("edit-dept").value;
    if (department_id === "all") department_id = 0;
        const data = {
            id: document.getElementById("edit-id").value,
            title: document.getElementById("edit-title").value.trim(),
            message: document.getElementById("edit-content").value.trim(),
            department_id: department_id,
            display_date: document.getElementById("edit-display").value
        };
        fetch("../../backend/api/announcement_api.php?action=update", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                alert("Announcement updated successfully!");
                document.getElementById("editAnnouncementModal").style.display = "none";
                loadAnnouncements();
            } else {
                alert("Failed to update announcement.");
            }
        });
    });


});
</script>


