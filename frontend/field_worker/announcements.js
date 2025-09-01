// announcements.js: Fetches and displays announcements dynamically on dashboard

document.addEventListener('DOMContentLoaded', function() {
    const annSection = document.querySelector('.card.announcements');
    if (!annSection) return;
    const container = document.createElement('div');
    annSection.appendChild(container);
    // Get today's date in YYYY-MM-DD format
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;
    // Get department_id from PHP (injected in dashboard.php)
    const departmentId = window.DEPARTMENT_ID || null;
    let url = `../../backend/api/announcement_api.php?action=list&display_date=${todayStr}`;
    if (departmentId) url += `&department_id=${departmentId}`;
    fetch(url)
        .then(res => res.json())
        .then(announcements => {
            container.innerHTML = '';
            if (!announcements.length) {
                container.innerHTML = '<p>No announcements for today.</p>';
                return;
            }
            announcements.forEach(a => {
                const p = document.createElement('p');
                let label = a.title ? `<strong>${a.title}:</strong> ` : '';
                p.innerHTML = `${label}${a.message}`;
                container.appendChild(p);
            });
        });
});
