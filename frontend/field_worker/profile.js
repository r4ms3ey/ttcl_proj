// profile.js: Handles profile form submission for field workers
document.addEventListener('DOMContentLoaded', function() {

    // Fetch departments from backend and populate the select
    fetchDepartments();

    document.getElementById('submitProfile').addEventListener('click', async function(e) {
        e.preventDefault(); // stop form default submit

        const full_name = document.getElementById('full_name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const department_id = document.getElementById('department').value;
        const group_name = document.getElementById('group_name').value;
        const college = document.getElementById('college').value.trim();
        const start_date = document.getElementById('start_date').value;
        const end_date = document.getElementById('end_date').value;
        const email = document.getElementById('email').value.trim();

        if (!full_name || !phone || !department_id || !email) {
            alert('Please fill in all required fields.');
            return;
        }

        try {
            const res = await fetch('../../backend/api/fieldworker_profile_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    full_name, phone, department_id, group_name, college, start_date, end_date, email
                })
            });
            const data = await res.json();
            if (data.success) {
                window.location.href = 'complete_password.php';
            } else {
                alert(data.message || 'Failed to save profile.');
            }
        } catch (err) {
            alert('Network error. Please try again.');
        }
    });


    function fetchDepartments() {
        console.log('Fetching departments...');
        fetch('../../backend/api/department_list.php')
            .then(res => res.json())
            .then(data => {
                console.log('Department API response:', data);
                const select = document.getElementById('department');
                if (!select) {
                    console.error('Department select element not found!');
                    return;
                }
                if (data.success && Array.isArray(data.departments)) {
                    data.departments.forEach(dep => {
                        const opt = document.createElement('option');
                        opt.value = dep.id;
                        opt.textContent = dep.name;
                        select.appendChild(opt);
                    });
                } else {
                    console.error('No departments found in API response.');
                }
            })
            .catch(err => {
                console.error('Error fetching departments:', err);
            });
    }
});