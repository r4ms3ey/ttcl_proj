<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/FieldWorker.php';
require_once __DIR__ . '/../models/User.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'field_worker') {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}

$current = $data['current_password'] ?? '';
$new = $data['new_password'] ?? '';
$confirm = $data['confirm_password'] ?? '';

if (!$current || !$new || !$confirm) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$user = User::findById($userId);
if (!$user || $user['password'] !== $current) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
    exit;
}
if (strlen($new) < 6) {
    echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters']);
    exit;
}
if ($new !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit;
}
$success = User::updatePassword($userId, $new);
if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update password']);
}
