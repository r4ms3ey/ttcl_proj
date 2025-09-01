// password.js: Handles password change form submission for field workers
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('submitPassword').addEventListener('click', async function() {
        const current_password = document.getElementById('current_password').value;
        const new_password = document.getElementById('new_password').value;
        const confirm_password = document.getElementById('confirm_password').value;

        if (!current_password || !new_password || !confirm_password) {
            alert('Please fill in all fields.');
            return;
        }
        if (new_password.length < 6) {
            alert('New password must be at least 6 characters.');
            return;
        }
        if (new_password !== confirm_password) {
            alert('Passwords do not match.');
            return;
        }

        const payload = {
            current_password,
            new_password,
            confirm_password
        };

        try {
            const res = await fetch('../../backend/api/fieldworker_password_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.success) {
                // Redirect to dashboard
                window.location.href = 'dashboard.php';
            } else {
                alert(data.message || 'Failed to update password.');
            }
        } catch (err) {
            alert('Network error. Please try again.');
        }
    });
});