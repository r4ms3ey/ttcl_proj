
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/Database.php';

try {
    $db = Database::getConnection();
    $stmt = $db->query('SELECT id, name FROM departments ORDER BY name ASC');
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($departments) {
        echo json_encode([
            'success' => true,
            'departments' => $departments
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'departments' => [],
            'message' => 'No departments found.'
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'departments' => [],
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
