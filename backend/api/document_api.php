<?php
require_once __DIR__ . '/../models/Document.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    // List all documents
    case 'list':
        echo json_encode(Document::getAll());
        break;

    // Get by ID
    case 'get':
        $id = intval($_GET['id']);
        echo json_encode(Document::getById($id));
        break;

    // Get by user
    case 'user':
        $user_id = intval($_GET['user_id']);
        echo json_encode(Document::getByUser($user_id));
        break;

    // Upload document (POST)
    case 'upload':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $type = $_POST['type'];

            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                require_once __DIR__ . '/../models/FieldWorker.php';
                $profile = FieldWorker::getProfile($user_id);
                $fullName = $profile && !empty($profile['full_name']) ? strtolower(preg_replace('/\s+/', '', $profile['full_name'])) : 'user' . $user_id;
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $fileName = $fullName . '_' . $type . '.' . $ext;
                $uploadDir = __DIR__ . '/../uploads/';
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

                $filePath = $uploadDir . $fileName;
                move_uploaded_file($_FILES['file']['tmp_name'], $filePath);

                $success = Document::upload($user_id, $type, $fileName, 'uploaded');
                echo json_encode(['success' => $success, 'file' => $fileName]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No file uploaded']);
            }
        }
        break;

    // Update status
    case 'update_status':
        $data = json_decode(file_get_contents("php://input"), true);
        $success = Document::updateStatus($data['id'], $data['status']);
        echo json_encode(['success' => $success]);
        break;

    // Delete document
    case 'delete':
    $id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
    echo json_encode(['success' => Document::delete($id)]);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}
