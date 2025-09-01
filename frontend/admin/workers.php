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

.modal-default-password {
    font-size: 0.9em;
    color: #666;
    margin-bottom: 10px;
    text-align: center;
}

.modal-default-password strong {
    color: #007bff;
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
    text-transform: capitalize;
}

.modal-body .form-group {
    margin-bottom: 15px;
   
}

.modal-body input[placeholder="Enter your full name"] {
    text-transform: uppercase;
}

.modal-body .row {
    display: flex;
    gap: 15px; /* Adjusted gap for better spacing */
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

.modal-body input, .modal-body select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 0.9em;
    background-color: #f8f9fa;
    
}

.modal-body input[type="date"] {
    padding: 10px;
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
</style>

<section class="field-workers">
    <h2 class="stat-icon-1"><i class="fas fa-users-cog"></i> Field Workers Management</h2>
    <p>Manage and view details of field workers</p>

    <div class="controls-container">
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search by name, department, or email">
        </div>
        <div class="controls">
            <button class="delete-btn" id="deleteSelectedBtn"><i class="fas fa-trash"></i> Delete Selected (0)</button>
            <button class="add-btn"><i class="fas fa-plus"></i> Add New Worker</button>
        </div>
    </div>

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Department</th>
                    <th>College/University</th>
                    <th>Email</th>
                    <th>Period</th>
                    <th>Group</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="workerTableBody">
                <!-- Dynamic rows will be injected here -->
            </tbody>
        </table>
        <p class="no-records" style="display:none;">No field workers found.</p>
    </div>
</section>

   <!-- Add New Worker Modal -->
<div id="addWorkerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Fied Worker</h2>
            <span class="close-modal">&times;</span>
        </div>
        <p class="modal-default-password">Create a new field worker account with default password ttcl1234</p>
        <div class="modal-body">
            <div class="form-group">
                <label>Username *</label>
                <input type="text" id="worker-username" placeholder="Enter username">
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="text" id="worker-password" value="ttcl123" readonly>
            </div>
            <div class="form-group">
                <label>Role *</label>
                <input type="text" id="worker-role" value="field_worker" readonly>
            </div>
            <button type="button" class="submit-modal-btn" id="add-worker-btn">Add field Worker</button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Add Worker submit
    document.getElementById("add-worker-btn").addEventListener("click", function() {

        const username = document.getElementById("worker-username").value.trim();
        const password = document.getElementById("worker-password").value;
        const role = document.getElementById("worker-role").value;

        if (!username || !password || !role) {
            alert("Please fill all required fields.");
            return;
        }

        const data = {
            username: username,
            password: password,
            role: role
        };

        fetch("/ttcl_proj/backend/api/fieldworker_api.php?action=create", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                alert("Field worker added successfully!");
                modal.style.display = 'none';
                fetchWorkers();
            } else {
                alert("Failed to add: " + (resp.message || "Unknown error"));
            }
        })
        .catch(err => {
            console.error("Add worker error:", err);
            alert("An error occurred while adding worker.");
        });
    });
    const addBtn = document.querySelector('.add-btn');
    const modal = document.getElementById('addWorkerModal');
    const closeModal = document.querySelector('.close-modal');

    // Show the modal when Add New Worker is clicked
    addBtn.addEventListener('click', function() {
        modal.style.display = 'block';
    });

    // Hide the modal when the close button is clicked
    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Hide the modal if the user clicks outside of it
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    fetchWorkers(); // Load all workers initially

    // Live search
    document.getElementById("searchInput").addEventListener("keyup", function() {
        fetchWorkers(this.value);
    });

    // Select all checkbox
    document.getElementById("selectAll").addEventListener("change", function() {
        const checkboxes = document.querySelectorAll(".selectWorker");
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateDeleteCount();
    });

    // Delete selected
    document.getElementById("deleteSelectedBtn").addEventListener("click", function() {
        const selectedIds = Array.from(document.querySelectorAll(".selectWorker:checked"))
            .map(cb => cb.value);
        if (!selectedIds.length) {
            alert("Please select workers to delete.");
            return;
        }
        if (!confirm("Are you sure you want to delete selected workers?")) return;

        Promise.all(selectedIds.map(id => deleteWorker(id, false)))
            .then(() => fetchWorkers());
    });
});

// Fetch workers from API
function fetchWorkers(search = "") {
    const action = search ? `search&q=${encodeURIComponent(search)}` : "getAll";
    fetch(`/ttcl_proj/backend/api/fieldworker_api.php?action=${action}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("workerTableBody");
            tbody.innerHTML = "";
            const noRecords = document.querySelector(".no-records");

            if (!data || !data.length) {
                noRecords.style.display = "block";
                return;
            }

            noRecords.style.display = "none";

            data.forEach(worker => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td><input type="checkbox" class="selectWorker" value="${worker.user_id}" onchange="updateDeleteCount()"></td>
                    <td>${worker.username || "N/A"}</td>
                    <td>${worker.full_name}</td>
                    <td>${worker.department_name || "N/A"}</td>
                    <td>${worker.college_name || "N/A"}</td>
                    <td>${worker.email || "N/A"}</td>
                    <td>${worker.start_date} to ${worker.end_date}</td>
                    <td><button class="group-btn">${worker.group_name || "N/A"}</button></td>
                    <td>${worker.role || "N/A"}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn" onclick="editWorker(${worker.user_id})"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" data-user-id="${worker.user_id}"><i style="color: rgba(122, 0, 0, 1);" class="fas fa-trash"></i></button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
            // Add event listeners for delete buttons (dynamic)
            tbody.querySelectorAll('.action-btn.delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    if (confirm('Are you sure you want to delete this worker?')) {
                        deleteWorker(userId);
                    }
                });
            });
        })
        .catch(err => console.error("Error fetching workers:", err));
}

// Update delete button count
function updateDeleteCount() {
    const count = document.querySelectorAll(".selectWorker:checked").length;
    document.getElementById("deleteSelectedBtn").innerHTML =
        `<i class="fas fa-trash"></i> Delete Selected (${count})`;
}

// Delete worker
function deleteWorker(id, showAlert = true) {
    return fetch(`/ttcl_proj/backend/api/fieldworker_api.php?action=delete&id=${id}`)
        .then(res => res.json())
        .then(resp => {
            if (resp.success && showAlert) alert("Worker deleted successfully.");
            return resp.success;
        })
        .catch(err => console.error("Delete error:", err));
}

// Edit worker placeholder
function editWorker(id) {
    alert("Edit worker profile with ID: " + id);
}
</script>