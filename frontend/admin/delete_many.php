<?php
// Bulk delete attendance records by IDs (expects POST with JSON: {ids: [1,2,3]})
require_once __DIR__ . '/../../backend/models/Attendance.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$ids = $input['ids'] ?? [];

if (!is_array($ids) || empty($ids)) {
    echo json_encode(['success' => false, 'message' => 'No IDs provided']);
    exit;
}

$success = Attendance::deleteMany($ids);
echo json_encode(['success' => $success]);
