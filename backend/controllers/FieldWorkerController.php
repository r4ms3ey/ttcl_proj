
<?php
// Include models and services for field worker logic
require_once '../models/FieldWorker.php';   // Field worker DB logic
require_once '../models/Attendance.php';    // Attendance DB logic
require_once '../services/GroupService.php';// Group scheduling logic
session_start(); // Ensure session is started for user context

// Controller for field worker actions
class FieldWorkerController
{
    // Complete profile after first login
    public static function completeProfile($data) {
        $userId = $_SESSION['user_id']; // Get current user ID
        return FieldWorker::saveProfile($userId, $data); // Save profile data
    }

    // Update profile after initial setup
    public static function updateProfile($data) {
        $userId = $_SESSION['user_id'];
        return FieldWorker::updateProfile($userId, $data); // Update profile data
    }

    // Change password for field worker
    public static function changePassword($newPassword) {
        $userId = $_SESSION['user_id'];
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT); // Hash password
        return FieldWorker::updatePassword($userId, $hashed); // Update password in DB
    }

    // Field worker check-in
    public static function checkIn() {
        $userId = $_SESSION['user_id'];
        // Check if group is scheduled today
        if (!GroupService::isTodayAllowed($userId)) {
            return ['error' => 'Your group is not scheduled today'];
        }
        // Record check-in with IP
        return Attendance::checkIn($userId, $_SERVER['REMOTE_ADDR']);
    }

    // Field worker check-out
    public static function checkOut() {
        $userId = $_SESSION['user_id'];
        // Record check-out with IP
        return Attendance::checkOut($userId, $_SERVER['REMOTE_ADDR']);
    }

    // Handle document upload (certificate/registration)
    public static function uploadDocument($file, $type) {
        $userId = $_SESSION['user_id'];
        return FieldWorker::handleUpload($userId, $file, $type); // Save file and update DB
    }

    // Get all field workers (admin use)
    public static function getAll() {
        return FieldWorker::getAllWorkers();
    }

    // Search field workers by query
    public static function search($query) {
        return FieldWorker::searchWorkers($query);
    }

    // Delete field worker by user ID
    public static function delete($id) {
        return FieldWorker::deleteById($id);
    }
}

// Handle API requests for field worker actions
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    switch ($_GET['action']) {
        case 'getAll':
            // Return all field workers
            echo json_encode(FieldWorkerController::getAll());
            break;
        case 'search':
            // Search field workers
            $query = $_GET['q'] ?? '';
            echo json_encode(FieldWorkerController::search($query));
            break;
        case 'delete':
            // Delete field worker
            $id = intval($_GET['id']);
            echo json_encode(['success' => FieldWorkerController::delete($id)]);
            break;
    }
}