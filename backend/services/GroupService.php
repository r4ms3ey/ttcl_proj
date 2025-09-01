<?php
require_once __DIR__ . '/../../config/Database.php';
class GroupService {
    public static function isTodayAllowed($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("           
            SELECT f.group_name, d.group_start_date
            FROM field_worker_profiles f
            JOIN departments d ON f.department_id = d.id
            WHERE f.user_id = ?
        ");
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false; 
        }

        $group = $row['group_name'];     // A or B
        $startDate = new DateTime($row['group_start_date']);
        $today = new DateTime();

    // Allow check-in for all groups on weekdays (Mon-Fri)
    return (int)$today->format('N') < 6;
    }
}
