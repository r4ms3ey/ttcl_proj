<?php
require_once __DIR__ . '/../models/FieldWorker.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {

    // Create a new field worker (user)
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['username'], $data['password'], $data['role'])) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                exit;
            }

            require_once __DIR__ . '/../models/User.php';
            $success = User::create([
                'username' => $data['username'],
                'password' => $data['password'], // plain text as requested
                'role' => $data['role']
            ]);

            echo json_encode(['success' => $success]);
        }
        break;

    // Get all field workers
    case 'getAll':
        echo json_encode(FieldWorker::getAllWorkers());
        break;

    // Search field workers
    case 'search':
        $query = $_GET['q'] ?? '';
        echo json_encode(FieldWorker::searchWorkers($query));
        break;

    // Delete a field worker
    case 'delete':
        $id = intval($_GET['id']);
        echo json_encode(['success' => FieldWorker::deleteById($id)]);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
