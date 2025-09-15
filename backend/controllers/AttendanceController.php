
<?php
// Include the Attendance model and supporting services
require_once __DIR__ . '/../../backend/models/Attendance.php'; // Attendance DB logic
require_once __DIR__ . '/../services/GroupService.php';        // Group scheduling logic
require_once __DIR__ . '/../services/LocationService.php';     // Location/IP validation

// Controller for attendance-related actions
class AttendanceController
{
    // Handles field worker check-in
    public static function checkIn() {
        $userId = $_SESSION['user_id']; // Get current user ID from session

        // Check if user's group is scheduled to work today
        if (!GroupService::isTodayAllowed($userId)) {
            return ['error' => 'Your group is not scheduled to work today.'];
        }

        // Prevent multiple check-ins per day
        if (Attendance::hasCheckedInToday($userId)) {
            return ['error' => 'You have already checked in today.'];
        }

        // Validate user's IP/location
        if (!LocationService::isAllowedIP($_SERVER['REMOTE_ADDR'])) {
            return ['error' => 'Check-in denied. Unauthorized location/IP.'];
        }

        // Record check-in in the database
        return Attendance::checkIn($userId);
    }

    // Handles field worker check-out
    public static function checkOut() {
        $userId = $_SESSION['user_id']; // Get current user ID from session

        // Must have checked in before checking out
        if (!Attendance::hasCheckedInToday($userId)) {
            return ['error' => 'You must check in before checking out.'];
        }

        // Prevent multiple check-outs per day
        if (Attendance::hasCheckedOutToday($userId)) {
            return ['error' => 'You have already checked out today.'];
        }

        // Record check-out in the database
        return Attendance::checkOut($userId);
    }

    // Admin: View attendance records, optionally filtered by date/department
    public static function view($date = null, $departmentId = null) {
        // Returns filtered attendance records
        return Attendance::filter($date, $departmentId);
    }

    // Admin: Delete multiple attendance records by IDs
    public static function deleteMany($ids = []) {
        // Bulk delete attendance records
        return Attendance::deleteMany($ids);
    }
}
