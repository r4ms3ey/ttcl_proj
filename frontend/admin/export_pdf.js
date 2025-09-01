// export_pdf.js: Handles export modal and PDF download for attendance
document.addEventListener('DOMContentLoaded', function() {

    // Create and insert modal only (no button)
    const modal = document.createElement('div');
    modal.className = 'export-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <h2>Export Attendance PDF</h2>
            <form id="exportForm">
                <label>Date Range:</label>
                <input type="date" id="start_date" required> to
                <input type="date" id="end_date" required><br><br>
                <label>Department:</label>
                <select id="department_select"><option value="all">All</option></select><br><br>
                <label>Group:</label>
                <select id="group_select">
                    <option value="all">All</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                </select><br><br>
                <button type="submit">Download PDF</button>
                <button type="button" id="closeExportModal">Cancel</button>
            </form>
        </div>
    `;
    // Add inline styles for modal visibility and centering
    modal.style.display = 'none';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.background = 'rgba(0,0,0,0.4)';
    modal.style.zIndex = '9999';
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';
    modal.style.display = 'none';
    modal.style.display = 'flex';
    // Style modal content
    setTimeout(() => {
        const content = modal.querySelector('.modal-content');
        if (content) {
            content.style.background = '#fff';
            content.style.padding = '30px 20px';
            content.style.borderRadius = '8px';
            content.style.maxWidth = '400px';
            content.style.margin = 'auto';
            content.style.position = 'relative';
            content.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
        }
    }, 0);
    modal.style.display = 'none';
    document.body.appendChild(modal);

    // Use the existing button in the HTML
    const exportBtn = document.getElementById('exportPdfBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', () => { modal.style.display = 'block'; });
    }
    modal.querySelector('#closeExportModal').onclick = () => { modal.style.display = 'none'; };

    // Populate departments
    fetch('/ttcl_proj/backend/api/department_list.php')
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            const contentType = res.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return res.text().then(text => { throw new Error('Not JSON: ' + text); });
            }
            return res.json();
        })
        .then(data => {
            if (data.success && Array.isArray(data.departments)) {
                const select = document.getElementById('department_select');
                data.departments.forEach(dep => {
                    const opt = document.createElement('option');
                    opt.value = dep.id;
                    opt.textContent = dep.name;
                    select.appendChild(opt);
                });
            }
        })
        .catch(err => {
            console.error('Department fetch error:', err);
            alert('Department fetch error: ' + err.message);
        });

    // Handle form submit
    document.getElementById('exportForm').onsubmit = function(e) {
        e.preventDefault();
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        const dept = document.getElementById('department_select').value;
        const group = document.getElementById('group_select').value;
        const url = `export_csv.php?start_date=${start}&end_date=${end}&department=${dept}&group=${group}`;
        window.open(url, '_blank');
        modal.style.display = 'none';
    };
});
