<!-- Template Management -->
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
    min-height: 400px; /* Adjusted for fewer fields */
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

.modal-body input[type="file"] {
    padding: 8px 10px; /* Adjusted for file input */
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
}
</style>

<section class="template-management">
    <h2>Template Management <i class="fas fa-file-download"></i></h2>
    <p>Upload Word document templates and set submission deadlines</p>
    <div class="template-controls">
        <button class="upload-btn"><i class="fas fa-upload"></i> Upload Template</button>
    </div>
    <div class="template-cards"></div>
</section>

<!-- Document Submissions -->
<section class="document-submission">
    <h2>Document Submission <i class="fas fa-upload"></i></h2>
    <p>Track and manage submitted documents by users.</p>
    <div class="submission-controls">
        <div class="search-bar">
            <input type="text" id="search-doc" placeholder="Search by ID or name...">
        </div>
        <div class="controls">
            <button class="download-selected-btn"><i class="fas fa-download"></i> Download Selected (0)</button>
            <button class="delete-selected-btn"><i class="fas fa-trash"></i> Delete Selected (0)</button>
        </div>
    </div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th><span class="circle-icon" title="Select all"><i class="far fa-circle"></i></span></th>
                    <th>ID</th>
                    <th>Fullname</th>
                    <th>Department</th>
                    <th>Certificate</th>
                    <th>Registration</th>
                    <th>Status</th>
                    <th>Submission Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="submission-table-body"></tbody>
        </table>
    </div>
</section>

    <!-- Upload Template Modal -->
    <div id="uploadTemplateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Upload Template</h2>
                <span class="close-modal">&times;</span>
            </div>
            <p class="modal-instruction">Upload Word document template for certificate and registration forms.</p>
            <div class="modal-body">
                <div class="form-group">
                    <label>Template Name *</label>
                    <input type="text" id="template-name" placeholder="Enter template name">
                </div>
                <div class="form-group">
                    <label>File Upload *</label>
                    <input type="file" id="template-file" accept=".doc,.docx">
                </div>
                <div class="form-group">
                    <label>Deadline Date *</label>
                    <input type="date" id="template-deadline">
                </div>
                <button type="button" class="submit-modal-btn" id="upload-template-btn">Upload Template</button>
            </div>
        </div>
    </div>

<script>

document.addEventListener("DOMContentLoaded", function() {
    const templateContainer = document.querySelector(".template-cards");
    const submissionTableBody = document.getElementById("submission-table-body");
    const searchInput = document.getElementById("search-doc");

    // ================= Templates Section =================
    function loadTemplates() {
        fetch("../../backend/api/admindocument_api.php?action=list")
        .then(res => res.json())
        .then(data => {
            templateContainer.innerHTML = "";
            if (!data.length) {
                templateContainer.innerHTML = "<p>No templates found</p>";
                return;
            }
            data.forEach(doc => {
                const card = document.createElement("div");
                card.className = "template-card";
                card.innerHTML = `
                    <h3>${doc.title}</h3>
                    <p>${doc.purpose ?? doc.title + '.docx'}</p>
                    <p>Deadline: ${doc.deadline ?? '-'}</p>
                    <button class="download-btn" onclick="downloadTemplate('${doc.file_name}')"><i class="fas fa-download"></i> Download</button>
                    <button class="update-btn" onclick="deleteTemplate(${doc.id})"><i class="fas fa-trash"></i> Delete</button>
                `;
                templateContainer.appendChild(card);
            });
        });
    }

    // Get the modal and buttons
    const uploadBtn = document.querySelector('.upload-btn');
    const modal = document.getElementById('uploadTemplateModal');
    const closeModal = modal.querySelector('.close-modal');

    // New: Get modal form fields
    const nameInput = document.getElementById("template-name");
    const fileInput = document.getElementById("template-file");
    const deadlineInput = document.getElementById("template-deadline");
    const submitBtn = document.getElementById("upload-template-btn");

    // Show the modal when Upload Template is clicked
    uploadBtn.addEventListener('click', function() {
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

    // Submit Upload
    submitBtn.addEventListener("click", () => {
        const name = nameInput.value.trim();
        const file = fileInput.files[0];
        const deadline = deadlineInput.value;

        if (!name || !file || !deadline) {
            alert("All fields are required");
            return;
        }

        const formData = new FormData();
        formData.append("title", name);
        formData.append("file", file);
        formData.append("deadline", deadline);

        fetch("../../backend/api/admindocument_api.php?action=upload", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                alert("Template uploaded successfully!");
                modal.style.display = "none";
                nameInput.value = "";
                fileInput.value = "";
                deadlineInput.value = "";
                loadTemplates();
            } else {
                alert("Upload failed: " + (resp.error || "Unknown error"));
            }
        })
        .catch(err => {
            console.error("Upload error:", err);
            alert("An error occurred while uploading.");
        });
    });

    window.downloadTemplate = function(fileName) {
        window.location.href = "../../backend/uploads/templates/" + fileName;
    }

    window.deleteTemplate = function(id) {
        if (!confirm("Delete this template?")) return;
        fetch(`../../backend/api/admindocument_api.php?action=delete&id=${id}`)
        .then(res => res.json())
        .then(resp => { if(resp.success) loadTemplates(); });
    }

    // ================= Submissions Section =================
    function updateSelectedCounts() {
        const selected = document.querySelectorAll('.selectSubmission:checked').length;
        document.querySelector('.download-selected-btn').innerHTML = `<i class="fas fa-download"></i> Download Selected (${selected})`;
        document.querySelector('.delete-selected-btn').innerHTML = `<i class="fas fa-trash"></i> Delete Selected (${selected})`;
    }

    function loadSubmissions(query = "") {
        fetch("../../backend/api/document_api.php?action=list")
        .then(res => res.json())
        .then(data => {
            submissionTableBody.innerHTML = "";
            if (!data.length) {
                submissionTableBody.innerHTML = "<tr><td colspan='9'>No submissions found</td></tr>";
                return;
            }

            data.filter(doc => 
                String(doc.id).includes(query) || String(doc.user_id).includes(query)
            ).forEach(doc => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td><input type="checkbox" class="selectSubmission" data-id="${doc.id}" data-certificate="${doc.certificate_file ?? ''}" data-registration="${doc.registration_file ?? ''}"></td>
                    <td>${submissionTableBody.children.length + 1}</td>
                    <td>${doc.full_name}</td>
                    <td>${doc.department ?? '-'}</td>
                    <td>${doc.certificate_status === 'uploaded' ? '<span class="status-data uploaded">Uploaded</span>' : '<span class="status-data missing">Missing</span>'}</td>
                    <td>${doc.registration_status === 'uploaded' ? '<span class="status-data uploaded">Uploaded</span>' : '<span class="status-data missing">Missing</span>'}</td>
                    <td>
                        ${
                            doc.certificate_status === 'uploaded' && doc.registration_status === 'uploaded'
                                ? '<span class="status-data uploaded">completed</span>'
                                : '<span class="status-data missing">missing</span>'
                        }
                    </td>
                    <td>${doc.last_uploaded ?? '-'}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn rounded download-both" data-certificate="${doc.certificate_file ?? ''}" data-registration="${doc.registration_file ?? ''}"><i class="fas fa-download"></i> Download</button>
                            <button class="action-btn delete rounded" onclick="deleteSubmission(${doc.id})"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </td>
                `;
                submissionTableBody.appendChild(row);
                // Add event listener for checkbox to update selected counts
                row.querySelector('.selectSubmission').addEventListener('change', updateSelectedCounts);
            });
            // Add event listeners for per-row download (both files)
            submissionTableBody.querySelectorAll('.download-both').forEach(btn => {
                btn.addEventListener('click', function() {
                    const cert = this.getAttribute('data-certificate');
                    const reg = this.getAttribute('data-registration');
                    if (cert && reg) {
                        window.open('../../backend/uploads/' + cert, '_blank');
                        window.open('../../backend/uploads/' + reg, '_blank');
                    } else if (cert) {
                        window.open('../../backend/uploads/' + cert, '_blank');
                    } else if (reg) {
                        window.open('../../backend/uploads/' + reg, '_blank');
                    } else {
                        alert('No files available for download.');
                    }
                });
            });
        });
    }

    searchInput.addEventListener("input", () => loadSubmissions(searchInput.value));

    // Select all functionality for the table
    document.querySelector('thead .circle-icon').addEventListener('click', function() {
        const all = document.querySelectorAll('.selectSubmission');
        const allChecked = Array.from(all).every(cb => cb.checked);
        all.forEach(cb => cb.checked = !allChecked);
        updateSelectedCounts();
    });

    // No longer needed: window.downloadSubmission

    window.deleteSubmission = function(document_id) {
        if (!confirm("Delete this submission?")) return;
        fetch(`../../backend/api/document_api.php?action=delete&id=${document_id}`)
        .then(res => res.json())
        .then(resp => { if(resp.success) loadSubmissions(searchInput.value); });
    }

    // Download Selected logic
    const downloadSelectedBtn = document.querySelector('.download-selected-btn');
    downloadSelectedBtn.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.selectSubmission:checked'));
        if (!selected.length) {
            alert('Please select at least one submission to download.');
            return;
        }
        let anyFile = false;
        selected.forEach(cb => {
            const cert = cb.getAttribute('data-certificate');
            const reg = cb.getAttribute('data-registration');
            if (cert) { window.open('../../backend/uploads/' + cert, '_blank'); anyFile = true; }
            if (reg) { window.open('../../backend/uploads/' + reg, '_blank'); anyFile = true; }
        });
        if (!anyFile) alert('No files available for selected submissions.');
    });

    // Delete Selected logic
    const deleteSelectedBtn = document.querySelector('.delete-selected-btn');
    deleteSelectedBtn.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.selectSubmission:checked'));
        if (!selected.length) {
            alert('Please select at least one submission to delete.');
            return;
        }
        if (!confirm('Are you sure you want to delete the selected submissions?')) return;
        let completed = 0;
        selected.forEach(cb => {
            const id = cb.getAttribute('data-id');
            fetch(`../../backend/api/document_api.php?action=delete&id=${id}`)
                .then(res => res.json())
                .then(resp => {
                    completed++;
                    if (completed === selected.length) {
                        loadSubmissions(searchInput.value);
                    }
                });
        });
    });

    // Initial load
    loadTemplates();
    loadSubmissions();
});
</script>