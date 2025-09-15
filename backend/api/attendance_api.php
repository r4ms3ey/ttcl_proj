
<?php
// Attendance API: handles HTTP requests for attendance actions
require_once __DIR__ . '/../models/Attendance.php'; // Attendance model

$action = $_GET['action'] ?? $_POST['action'] ?? null; // Get action from request

switch ($action) {
    // Get today's attendance for a user
    case 'getToday':
        $userId = $_GET['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            exit;
        }
        $record = Attendance::getToday($userId); // Fetch today's record
        echo json_encode($record ?: []); // Return record or empty
        exit;

    // Handle check-in request
    case 'checkin':
        $input = json_decode(file_get_contents('php://input'), true); // Get JSON input
        $userId = $input['user_id'] ?? null;
        $lat = $input['latitude'] ?? null;
        $lng = $input['longitude'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            exit;
        }
        if ($lat === null || $lng === null) {
            echo json_encode(['success' => false, 'message' => 'Missing latitude or longitude']);
            exit;
        }
        require_once __DIR__ . '/../services/LocationService.php'; // Location validation
        // Check if user is within allowed location (100m radius)
        if (!LocationService::isWithinAllowedLocation(floatval($lat), floatval($lng), 100)) {
            echo json_encode(['success' => false, 'message' => 'Check-in denied. You are not at the allowed location.']);
            exit;
        }
        $result = Attendance::checkIn($userId, 'checkin'); // Record check-in
        echo json_encode($result); // Return result
        exit;

    // Handle check-out request
    case 'checkout':
        $input = json_decode(file_get_contents('php://input'), true); // Get JSON input
        $userId = $input['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            exit;
        }
        $result = Attendance::checkOut($userId); // Record check-out
        echo json_encode($result); // Return result
        exit;

    // Set allowed location for attendance (admin)
    case 'setLocation':
        $input = json_decode(file_get_contents('php://input'), true); // Get JSON input
        $lat = $input['latitude'] ?? null;
        $lng = $input['longitude'] ?? null;
        if ($lat === null || $lng === null) {
            echo json_encode(['success' => false, 'message' => 'Missing latitude or longitude']);
            exit;
        }
        require_once __DIR__ . '/../models/Settings.php'; // Settings model
        $locationData = json_encode(['latitude' => $lat, 'longitude' => $lng]);
        $success = Settings::set('attendance_location', $locationData); // Save location
        echo json_encode(['success' => $success]);
        exit;

    // Delete a single attendance record
    case 'delete':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(["success" => false, "message" => "Missing ID"]);
            exit;
        }
        $success = Attendance::delete($id); // Delete record
        echo json_encode(["success" => $success]);
        break;

    // Bulk delete attendance records
    case 'deleteMany':
        $input = json_decode(file_get_contents("php://input"), true);
        $ids = $input['ids'] ?? [];
        $success = Attendance::deleteMany($ids); // Bulk delete
        echo json_encode(["success" => $success]);
        break;

    // Get all attendance records (admin)
    case 'getAll':
        $search = $_GET['search'] ?? '';
        $department = $_GET['department'] ?? 'all';
        $date = $_GET['date'] ?? null;
        $records = Attendance::getAll($search, $department, $date); // Fetch records
        echo json_encode($records);
        break;

    // Invalid action
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}
