<?php
require_once __DIR__ . '/../../backend/controllers/AttendanceController.php';
require_once __DIR__ . '/../../backend/models/Department.php';

// Capture filters from query string
$search = $_GET['search'] ?? '';
$department = $_GET['department'] ?? 'all';
$date = $_GET['date'] ?? null;

// Fetch data
$departments = Department::getAll();
$attendanceRecords = Attendance::getAll($search, $department, $date);
?>
<section class="attendance">
    <h2 class="stat-icon-1"><i class="fas fa-calendar-check"></i>  Attendance Records</h2>
    <p>View and manage daily attendance records for field workers</p>

    <div class="attendance-controls">
        <div class="search-filter-row">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search by name" 
                       value="<?= htmlspecialchars($search) ?>">
            </div>
            
            <div class="filter-section">
                <select name="department">
                    <option value="all" <?= $department === 'all' ? 'selected' : '' ?>>All Departments</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept['name']) ?>" 
                            <?= $department === $dept['name'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="group" id="groupFilter">
                    <option value="all">All Groups</option>
                </select>
                <input type="date" name="date" value="<?= htmlspecialchars($date ?? date('Y-m-d')) ?>">
            </div>
        </div>

        <div class="controls">
            <button type="button" class="delete-btn" id="deleteSelectedBtn"><i class="fas fa-trash"></i> Delete Selected</button>
            <button type="button" id="exportPdfBtn" class="btn">Export Attendance PDF</button>
            <button type="button" class="btn" id="setLocationBtn"><i class="fas fa-map-marker-alt"></i> Set Location</button>
        </div>
    </div>


    <script src="export_pdf.js"></script>

    <!-- Modal for setting location (not nested in any div) -->
    <div id="locationModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:1000;align-items:center;justify-content:center;">
        <div style="background:#fff;padding:30px 20px;border-radius:8px;max-width:350px;margin:auto;position:relative;">
            <h3>Set Attendance Location</h3>
            <form id="locationForm">
                <label>Latitude:<br><input type="number" step="any" id="latitude" required></label><br><br>
                <label>Longitude:<br><input type="number" step="any" id="longitude" required></label><br><br>
                <button type="button" id="getCurrentLocBtn">Use My Location</button>
                <button type="submit" class="btn">Save Location</button>
                <button type="button" id="closeLocationModal" style="float:right;">Cancel</button>
            </form>
        </div>
    </div>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Date</th>
                        <th>Group</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Total Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>help 
                <tbody id="attendance-table-body">
                    <!-- Rows injected dynamically -->
                </tbody>

            </table>
        </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Initial load: all records
    loadAttendance();

    const searchInput = document.querySelector("input[name='search']");
    const deptSelect = document.querySelector("select[name='department']");
    const groupSelect = document.querySelector("select[name='group']");
    const dateInput = document.querySelector("input[name='date']");

    // Live filters
    [searchInput, deptSelect, groupSelect, dateInput].forEach(el => {
        if (!el) return;
        el.addEventListener("change", () => applyFilters());
        if (el.tagName === 'INPUT') el.addEventListener("keyup", () => applyFilters()); // for search input
    });

    // Select all checkboxes
    document.getElementById("select-all").addEventListener("change", function() {
        const checkboxes = document.querySelectorAll(".record-checkbox");
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Bulk delete
    document.getElementById("deleteSelectedBtn").addEventListener("click", function() {
        const selected = Array.from(document.querySelectorAll(".record-checkbox:checked")).map(cb => cb.value);
        if (!selected.length) return alert("No records selected.");
        if (!confirm("Are you sure you want to delete selected records?")) return;

        fetch("../../backend/api/attendance_api.php?action=deleteMany", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ids: selected})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) loadAttendance(getCurrentFilters());
            else alert("Failed to delete records.");
        });
    });

    // Set Location Modal logic
    const setLocationBtn = document.getElementById('setLocationBtn');
    const locationModal = document.getElementById('locationModal');
    const closeLocationModal = document.getElementById('closeLocationModal');
    const getCurrentLocBtn = document.getElementById('getCurrentLocBtn');
    const locationForm = document.getElementById('locationForm');
    setLocationBtn.addEventListener('click', () => {
        locationModal.style.display = 'flex';
    });
    closeLocationModal.addEventListener('click', () => {
        locationModal.style.display = 'none';
    });
    getCurrentLocBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (!navigator.geolocation) return alert('Geolocation not supported.');
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('latitude').value = pos.coords.latitude;
            document.getElementById('longitude').value = pos.coords.longitude;
        }, function() {
            alert('Unable to get location.');
        });
    });
    locationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        fetch('../../backend/api/attendance_api.php?action=setLocation', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ latitude: lat, longitude: lng })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('locationModal').style.display = 'none';
                alert('Location saved!');
            } else {
                alert(data.message || 'Failed to save location.');
            }
        });
    });
});

// Collect current filter values
function getCurrentFilters() {
    return {
        search: document.querySelector("input[name='search']").value,
        department: document.querySelector("select[name='department']").value,
        group: document.querySelector("select[name='group']")?.value || 'all',
        date: document.querySelector("input[name='date']").value
    };
}

// Apply current filters
function applyFilters() {
    loadAttendance(getCurrentFilters());
}

// Load attendance from API
function loadAttendance(filters = {}) {
    const params = new URLSearchParams(filters).toString();
    fetch(`../../backend/api/attendance_api.php?action=getAll&${params}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("attendance-table-body");
            tbody.innerHTML = "";

            // Dynamically populate group filter dropdown
            const groupSelect = document.getElementById('groupFilter');
            if (groupSelect) {
                const prev = groupSelect.value;
                const groups = Array.from(new Set(data.map(row => row.group_name).filter(g => g)));
                groupSelect.innerHTML = '<option value="all">All Groups</option>' + groups.map(g => `<option value="${g}">${g}</option>`).join('');
                groupSelect.value = prev && groups.includes(prev) ? prev : 'all';
            }

            if (!data || !data.length) {
                tbody.innerHTML = `<tr><td colspan="11" class="no-records">No attendance records found.</td></tr>`;
                return;
            }

            data.forEach(row => {
                const tr = document.createElement("tr");
                // Only render checkbox if row.id is present
                const checkbox = row.id ? `<input type="checkbox" class="record-checkbox" value="${row.id}">` : '';
                tr.innerHTML = `
                    <td>${checkbox}</td>
                    <td>${row.id ?? ''}</td>
                    <td>${row.full_name}</td>
                    <td>${row.department}</td>
                    <td>${row.date}</td>
                    <td>${row.group_name ?? '-'}</td>
                    <td>${row.check_in ?? '-'}<\/td>
                    <td>${row.check_out ?? "-"}</td>
                    <td>${row.total_hours ?? "-"}</td>
                    <td>${row.status}</td>
                    <td>
                        ${row.id ? `<a class=\"action-btn delete\" href=\"#\" data-id=\"${row.id}\"><i style=\"color: rgba(122, 0, 0, 1);\" class=\"fas fa-trash\"></i></a>` : ''}
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Attach delete event listeners for single delete (after rows are rendered)
            document.querySelectorAll('.action-btn.delete').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    if (!id) return;
                    if (!confirm('Delete this record?')) return;
                    fetch('../../backend/api/attendance_api.php?action=delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'id=' + encodeURIComponent(id)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) loadAttendance(getCurrentFilters());
                        else alert('Failed to delete record.');
                    });
                });
            });
        })
        .catch(err => console.error("Error loading attendance:", err));
}
</script>