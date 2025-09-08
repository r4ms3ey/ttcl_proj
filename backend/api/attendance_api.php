
<?php
require_once __DIR__ . '/../models/Attendance.php';

$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'getToday':
        $userId = $_GET['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            exit;
        }
        $record = Attendance::getToday($userId);
        echo json_encode($record ?: []);
        exit;
    case 'checkin':
        $input = json_decode(file_get_contents('php://input'), true);
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
        require_once __DIR__ . '/../services/LocationService.php';
        if (!LocationService::isWithinAllowedLocation(floatval($lat), floatval($lng), 100)) {
            echo json_encode(['success' => false, 'message' => 'Check-in denied. You are not at the allowed location.']);
            exit;
        }
        $result = Attendance::checkIn($userId, 'checkin');
        echo json_encode($result);
        exit;
    case 'checkout':
        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $input['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            exit;
        }
    $result = Attendance::checkOut($userId);
    echo json_encode($result);
    exit;
    case 'setLocation':
        $input = json_decode(file_get_contents('php://input'), true);
        $lat = $input['latitude'] ?? null;
        $lng = $input['longitude'] ?? null;
        if ($lat === null || $lng === null) {
            echo json_encode(['success' => false, 'message' => 'Missing latitude or longitude']);
            exit;
        }
        require_once __DIR__ . '/../models/Settings.php';
        $locationData = json_encode(['latitude' => $lat, 'longitude' => $lng]);
        $success = Settings::set('attendance_location', $locationData);
        echo json_encode(['success' => $success]);
        exit;
    case 'delete':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(["success" => false, "message" => "Missing ID"]);
            exit;
        }
        $success = Attendance::delete($id);
        echo json_encode(["success" => $success]);
        break;
    case 'deleteMany':
        $input = json_decode(file_get_contents("php://input"), true);
        $ids = $input['ids'] ?? [];
        $success = Attendance::deleteMany($ids);
        echo json_encode(["success" => $success]);
        break;
    case 'getAll':
        $search = $_GET['search'] ?? '';
        $department = $_GET['department'] ?? 'all';
        $date = $_GET['date'] ?? null;
        $records = Attendance::getAll($search, $department, $date);
        echo json_encode($records);
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid action"]);
}
