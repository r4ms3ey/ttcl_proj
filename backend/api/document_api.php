
<?php
// Document API: handles HTTP requests for document actions
require_once __DIR__ . '/../models/Document.php'; // Document model
header('Content-Type: application/json'); // Set response type

$action = $_GET['action'] ?? ''; // Get action from request

switch ($action) {
    // List all documents
    case 'list':
        echo json_encode(Document::getAll()); // Return all documents
        break;

    // Get document by ID
    case 'get':
        $id = intval($_GET['id']);
        echo json_encode(Document::getById($id)); // Return document by ID
        break;

    // Get documents by user ID
    case 'user':
        $user_id = intval($_GET['user_id']);
        echo json_encode(Document::getByUser($user_id)); // Return user's documents
        break;

    // Upload document (POST)
    case 'upload':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id']; // Get user ID
            $type = $_POST['type'];      // Get document type

            // Check if file is uploaded
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                require_once __DIR__ . '/../models/FieldWorker.php'; // For profile info
                $profile = FieldWorker::getProfile($user_id); // Get user profile
                // Build file name from user's full name and type
                $fullName = $profile && !empty($profile['full_name']) ? strtolower(preg_replace('/\s+/', '', $profile['full_name'])) : 'user' . $user_id;
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); // Get file extension
                $fileName = $fullName . '_' . $type . '.' . $ext; // Final file name
                $uploadDir = __DIR__ . '/../uploads/'; // Upload directory
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true); // Create dir if needed

                $filePath = $uploadDir . $fileName; // Full file path
                move_uploaded_file($_FILES['file']['tmp_name'], $filePath); // Move file

                // Save document record in DB
                $success = Document::upload($user_id, $type, $fileName, 'uploaded');
                echo json_encode(['success' => $success, 'file' => $fileName]); // Return result
            } else {
                echo json_encode(['success' => false, 'error' => 'No file uploaded']); // Error
            }
        }
        break;

    // Update document status
    case 'update_status':
        $data = json_decode(file_get_contents("php://input"), true); // Get JSON input
        $success = Document::updateStatus($data['id'], $data['status']); // Update status
        echo json_encode(['success' => $success]); // Return result
        break;

    // Delete document by ID
    case 'delete':
        $id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
        echo json_encode(['success' => Document::delete($id)]); // Delete and return result
        break;

    // Invalid action
    default:
        echo json_encode(['error' => 'Invalid action']); // Error response
}
