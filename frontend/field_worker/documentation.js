// documentation.js: Handles fetching templates, uploading documents, and deadline reminders for dashboard.php

document.addEventListener('DOMContentLoaded', function() {
    const userId = window.USER_ID || null; // Set by PHP
    const docSection = document.querySelector('.card.documentation');
    if (!docSection) return;

    // Fetch templates from backend
    fetch('../../backend/api/admindocument_api.php?action=list')
        .then(res => res.json())
        .then(templates => {
            const downloadDiv = docSection.querySelector('.download-templates');
            if (downloadDiv) downloadDiv.innerHTML = '';
            templates.forEach(tpl => {
                // Show deadline reminder above the respective download button if exists and in future
                if (tpl.deadline && new Date(tpl.deadline) > new Date()) {
                    const deadlineAlert = document.createElement('div');
                    deadlineAlert.className = 'deadline-alert';
                    deadlineAlert.innerHTML = `<strong>Reminder:</strong> Submit your ${tpl.title} before the deadline, ${tpl.deadline}`;
                    downloadDiv.appendChild(deadlineAlert);
                }
                // Add download button
                const btn = document.createElement('a');
                btn.className = 'download-btn';
                btn.innerHTML = `<i class="fas fa-download"></i> ${tpl.title}`;
                btn.href = `../../backend/uploads/templates/${tpl.file_name}`;
                btn.download = tpl.file_name;
                downloadDiv.appendChild(btn);
            });
        });

    // Handle uploads
    docSection.querySelectorAll('.upload-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const type = form.dataset.type;
            const fileInput = form.querySelector('input[type="file"]');
            if (!fileInput.files.length) {
                alert('Please select a file to upload.');
                return;
            }
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('type', type);
            formData.append('file', fileInput.files[0]);
            fetch('../../backend/api/document_api.php?action=upload', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Upload successful!');
                    fileInput.value = '';
                } else {
                    alert(data.error || 'Upload failed.');
                }
            });
        });
    });
});
