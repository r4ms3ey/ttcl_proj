// dashboard_stats.php
<?php
require_once __DIR__ . '/../../config/database.php';
$conn = Database::getConnection();

$totalWorkers = $conn->query("SELECT COUNT(*) as total FROM field_worker_profiles")->fetch(PDO::FETCH_ASSOC)['total'];
$presentToday = $conn->query("SELECT COUNT(*) as total FROM attendance WHERE DATE(created_at)=CURDATE() AND status='Present'")->fetch(PDO::FETCH_ASSOC)['total'];
$totalDepartments = $conn->query("SELECT COUNT(*) as total FROM departments")->fetch(PDO::FETCH_ASSOC)['total'];
$pendingDocs = $conn->query("
    SELECT COUNT(*) as total 
    FROM documents d
    WHERE d.status='uploaded'
      AND d.user_id IN (
          SELECT user_id
          FROM documents
          WHERE status='uploaded'
          GROUP BY user_id
          HAVING COUNT(*) = 1
      )
")->fetch(PDO::FETCH_ASSOC)['total'];

echo json_encode([
    'totalWorkers' => $totalWorkers,
    'presentToday' => $presentToday,
    'totalDepartments' => $totalDepartments,
    'pendingDocs' => $pendingDocs
]);
