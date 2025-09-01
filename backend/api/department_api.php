<?php
require_once __DIR__ . '/../models/Department.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? null;

switch ($action) {
    case 'getAll':
        $departments = Department::getAll();
        echo json_encode($departments);
        break;

    case 'get':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $dept = Department::getById($id);
            echo json_encode($dept ?: []);
        } else {
            echo json_encode(['error' => 'Missing id']);
        }
        break;

    case 'create':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || empty($data['name'])) {
            echo json_encode(["success" => false, "message" => "Invalid input"]);
            exit;
        }
        $success = Department::create($data);
        echo json_encode(["success" => $success]);
        break;

    case 'update':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || empty($data['id'])) {
            echo json_encode(["success" => false, "message" => "Missing department ID"]);
            exit;
        }
        $success = Department::update($data['id'], $data);
        echo json_encode(["success" => $success]);
        break;

    case 'delete':
        if (!isset($_POST['id'])) {
            echo json_encode(["success" => false, "message" => "Missing department ID"]);
            exit;
        }
        $id = intval($_POST['id']);
        $success = Department::delete($id);
        echo json_encode(["success" => $success]);
        break;

    case 'list':
        echo json_encode(Department::getAll());
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}
