<?php
// New approach: upsert profile directly, no FieldWorker model required
session_start();
require_once __DIR__ . '/../../config/Database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'field_worker') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

$required = ['full_name', 'phone', 'department_id', 'email'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        echo json_encode(['success' => false, 'message' => 'Missing required field: ' . $field]);
        exit;
    }
}

// Prepare data
$full_name = trim($input['full_name']);
$phone = trim($input['phone']);
$department_id = (int)$input['department_id'];
$group_name = isset($input['group_name']) ? trim($input['group_name']) : null;
$college = isset($input['college']) ? trim($input['college']) : (isset($input['college_name']) ? trim($input['college_name']) : null);
$start_date = !empty($input['start_date']) ? $input['start_date'] : null;
$end_date = !empty($input['end_date']) ? $input['end_date'] : null;
$email = trim($input['email']);

try {
    $db = Database::getConnection();
    // Upsert profile (insert or update)
    $stmt = $db->prepare('SELECT user_id FROM field_worker_profiles WHERE user_id = ?');
    $stmt->execute([$userId]);
    if ($stmt->fetch()) {
        // Update
    $update = $db->prepare('UPDATE field_worker_profiles SET full_name=?, phone=?, department_id=?, group_name=?, college_name=?, start_date=?, end_date=?, email=? WHERE user_id=?');
    $update->execute([$full_name, $phone, $department_id, $group_name, $college, $start_date, $end_date, $email, $userId]);
    } else {
        // Insert
    $insert = $db->prepare('INSERT INTO field_worker_profiles (user_id, full_name, phone, department_id, group_name, college_name, start_date, end_date, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $insert->execute([$userId, $full_name, $phone, $department_id, $group_name, $college, $start_date, $end_date, $email]);
    }
    echo json_encode(['success' => true, 'message' => 'Profile saved']);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $e->getMessage()]);
}
