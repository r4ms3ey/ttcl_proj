// attendance.js: Handles check-in/check-out with location and backend integration

document.addEventListener('DOMContentLoaded', function() {
    const userId = window.USER_ID;
    const attendanceCard = document.querySelector('.card.attendance');
    if (!attendanceCard) return;
    const checkInBtn = attendanceCard.querySelector('button:nth-of-type(1)');
    const checkOutBtn = attendanceCard.querySelector('button:nth-of-type(2)');

    function getLocationAndSend(type) {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }
        checkInBtn.disabled = true;
        checkOutBtn.disabled = true;
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const payload = {
                user_id: userId,
                type: type,
                latitude: lat,
                longitude: lng
            };
            fetch(`../../backend/api/attendance_api.php?action=${type}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(type === 'checkin' ? 'Check-in successful!' : 'Check-out successful!');
                } else {
                    alert(data.message || 'Attendance action failed.');
                }
                checkInBtn.disabled = false;
                checkOutBtn.disabled = false;
            })
            .catch(() => {
                alert('Network error.');
                checkInBtn.disabled = false;
                checkOutBtn.disabled = false;
            });
        }, function() {
            alert('Unable to retrieve your location.');
            checkInBtn.disabled = false;
            checkOutBtn.disabled = false;
        });
    }

    checkInBtn.addEventListener('click', function() {
        getLocationAndSend('checkin');
    });
    checkOutBtn.addEventListener('click', function() {
        getLocationAndSend('checkout');
    });
});
