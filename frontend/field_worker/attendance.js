// attendance.js: Handles check-in/check-out with location and backend integration

document.addEventListener('DOMContentLoaded', function() {
    // Get the current user's ID from global JS variable
    const userId = window.USER_ID;
    // Find the attendance card element in the DOM
    const attendanceCard = document.querySelector('.card.attendance');
    if (!attendanceCard) return; // Exit if not found
    // Get the check-in and check-out buttons
    const checkInBtn = attendanceCard.querySelector('button:nth-of-type(1)');
    const checkOutBtn = attendanceCard.querySelector('button:nth-of-type(2)');

    // Helper function to get location and send attendance request
    function getLocationAndSend(type) {
        // Check if browser supports geolocation
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }
        // Disable buttons to prevent multiple submissions
        checkInBtn.disabled = true;
        checkOutBtn.disabled = true;
        // Get current position from browser
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude; // Latitude
            const lng = position.coords.longitude; // Longitude
            // Build payload for API
            const payload = {
                user_id: userId,
                type: type, // 'checkin' or 'checkout'
                latitude: lat,
                longitude: lng
            };
            // Send POST request to attendance API
            fetch(`../../backend/api/attendance_api.php?action=${type}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json()) // Parse JSON response
            .then(data => {
                // Show success or error message
                if (data.success) {
                    alert(type === 'checkin' ? 'Check-in successful!' : 'Check-out successful!');
                } else {
                    alert(data.message || 'Attendance action failed.');
                }
                // Re-enable buttons
                checkInBtn.disabled = false;
                checkOutBtn.disabled = false;
            })
            .catch(() => {
                alert('Network error.');
                checkInBtn.disabled = false;
                checkOutBtn.disabled = false;
            });
        }, function() {
            // Error callback if location cannot be retrieved
            alert('Unable to retrieve your location.');
            checkInBtn.disabled = false;
            checkOutBtn.disabled = false;
        });
    }

    // Add event listeners for check-in and check-out buttons
    checkInBtn.addEventListener('click', function() {
        getLocationAndSend('checkin'); // Trigger check-in
    });
    checkOutBtn.addEventListener('click', function() {
        getLocationAndSend('checkout'); // Trigger check-out
    });
}); // <-- Fix: Ensure file ends with correct closing brace
