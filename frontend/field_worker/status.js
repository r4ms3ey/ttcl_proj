// status.js: Fetches today's attendance status and updates the dashboard in real time

document.addEventListener('DOMContentLoaded', function() {
    const statusCard = document.querySelector('.card.status');
    if (!statusCard) return;
    const timeP = statusCard.querySelector('p');
    const statusSpan = statusCard.querySelector('.status-text');
    const userId = window.USER_ID;

    function updateTime() {
        const now = new Date();
        // 24hr format HH:MM:SS
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        timeP.textContent = `${h}:${m}:${s}`;
    }
    setInterval(updateTime, 1000);
    updateTime();

    function updateStatus() {
        fetch(`../../backend/api/attendance_api.php?action=getToday&user_id=${userId}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.checkin_time) {
                    if (data.checkout_time) {
                        statusSpan.textContent = 'Checked Out';
                        statusSpan.style.color = '#d32f2f';
                    } else {
                        statusSpan.textContent = 'Checked In';
                        statusSpan.style.color = '#388e3c';
                    }
                } else {
                    statusSpan.textContent = 'Not Checked In';
                    statusSpan.style.color = '#b71c1c';
                }
            });
    }
    updateStatus();
    setInterval(updateStatus, 60000); // update every minute
});
