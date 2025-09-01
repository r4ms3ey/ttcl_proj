<?php
require_once __DIR__ . '/../../config/database.php';

class Attendance
{
    // Single delete attendance record by ID
    public static function delete($id) {
        if (!$id || !is_numeric($id)) return false;
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM attendance WHERE id = ?");
        return $stmt->execute([$id]);
    }
    // Bulk delete attendance records by IDs
    public static function deleteMany($ids) {
        if (!is_array($ids) || empty($ids)) return false;
        $db = Database::getConnection();
        // Only allow numeric IDs
        $ids = array_filter($ids, 'is_numeric');
        if (empty($ids)) return false;
        $in = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $db->prepare("DELETE FROM attendance WHERE id IN ($in)");
        return $stmt->execute($ids);
    }

// ...existing code...
    // 1. Record a check-in
    public static function checkIn($userId, $type = 'checkin') {
        require_once __DIR__ . '/../services/GroupService.php';
        require_once __DIR__ . '/Department.php';
        $db = Database::getConnection();

        // 1. Block if not a valid group day
        if (!\GroupService::isTodayAllowed($userId)) {
            return ['success' => false, 'message' => 'Check-in not allowed today (not your group day).'];
        }

        // 2. Get department check-in time limit
        $profileStmt = $db->prepare("SELECT department_id FROM field_worker_profiles WHERE user_id = ?");
        $profileStmt->execute([$userId]);
        $profile = $profileStmt->fetch(PDO::FETCH_ASSOC);
        if (!$profile) {
            return ['success' => false, 'message' => 'Profile not found.'];
        }
        $department = Department::getById($profile['department_id']);
        if (!$department) {
            return ['success' => false, 'message' => 'Department not found.'];
        }
        $checkInLimit = $department['check_in_limit'] ?? '09:00:00';
        $now = date('H:i:s');
        if ($now > $checkInLimit) {
            return ['success' => false, 'message' => 'Check-in not allowed after ' . $checkInLimit];
        }

        // Prevent multiple check-ins per day
        $stmt = $db->prepare("SELECT id FROM attendance WHERE user_id = ? AND DATE(checkin_time) = CURDATE()");
        $stmt->execute([$userId]);
        if ($stmt->fetch()) return ['success' => false, 'message' => 'Already checked in today.'];

        $stmt = $db->prepare("INSERT INTO attendance (user_id, type, checkin_time) VALUES (?, ?, NOW())");
        $ok = $stmt->execute([$userId, $type]);
        return $ok ? ['success' => true] : ['success' => false, 'message' => 'DB error on check-in.'];
    }

    // 2. Record a check-out
    public static function checkOut($userId) {
        require_once __DIR__ . '/Department.php';
        $db = Database::getConnection();

        // Must already have a check-in today
        $stmt = $db->prepare("SELECT id FROM attendance WHERE user_id = ? AND DATE(checkin_time) = CURDATE()");
        $stmt->execute([$userId]);
        $record = $stmt->fetch();

        if (!$record) return ['success' => false, 'message' => 'Check-in required before check-out.'];

        // Get department check-out time limit
        $profileStmt = $db->prepare("SELECT department_id FROM field_worker_profiles WHERE user_id = ?");
        $profileStmt->execute([$userId]);
        $profile = $profileStmt->fetch(PDO::FETCH_ASSOC);
        if (!$profile) {
            return ['success' => false, 'message' => 'Profile not found.'];
        }
        $department = Department::getById($profile['department_id']);
        if (!$department) {
            return ['success' => false, 'message' => 'Department not found.'];
        }
        $checkOutLimit = $department['check_out_limit'] ?? '17:00:00';
        $now = date('H:i:s');
        if ($now > $checkOutLimit) {
            return ['success' => false, 'message' => 'Check-out not allowed after ' . $checkOutLimit];
        }

        $stmt = $db->prepare("UPDATE attendance SET checkout_time = NOW() WHERE id = ?");
        $ok = $stmt->execute([$record['id']]);
        return $ok ? ['success' => true] : ['success' => false, 'message' => 'DB error on check-out.'];
    }

    // 3. Get today's attendance for a user
    public static function getToday($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM attendance WHERE user_id = ? AND DATE(checkin_time) = CURDATE()");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Get full attendance for a user
    public static function getByUser($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM attendance WHERE user_id = ? ORDER BY checkin_time DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
// 5. Get all attendance logs (admin use)
public static function getAll($search = '', $department = 'all', $date = null) {
    $db = Database::getConnection();

    // Get all unique dates from attendance table
    $datesStmt = $db->query("SELECT DISTINCT DATE(checkin_time) as date FROM attendance ORDER BY date DESC");
    $dates = $datesStmt->fetchAll(PDO::FETCH_COLUMN);
    if (empty($dates)) {
        // If no attendance records, use today
        $dates = [date('Y-m-d')];
    }

    // Get all workers, including username, group, and start_date
    $workersStmt = $db->query("SELECT f.user_id, f.full_name, d.name AS department, u.username, f.group_name, f.start_date FROM field_worker_profiles f LEFT JOIN departments d ON f.department_id = d.id LEFT JOIN users u ON u.id = f.user_id");
    $workers = $workersStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get group filter from $_GET or $_POST if available
    $group = $_GET['group'] ?? $_POST['group'] ?? 'all';

    $results = [];
    foreach ($workers as $worker) {
        $workerStart = $worker['start_date'] ?? null;
        // Apply group filter
        if ($group !== 'all' && (!isset($worker['group_name']) || strtoupper($worker['group_name']) !== strtoupper($group))) {
            continue;
        }
        foreach ($dates as $dateVal) {
            // Only show attendance from worker's start date
            if ($workerStart && $dateVal < $workerStart) continue;
            // Search for an attendance record for this worker and date
            $attStmt = $db->prepare("SELECT * FROM attendance WHERE user_id = ? AND DATE(checkin_time) = ?");
            $attStmt->execute([$worker['user_id'], $dateVal]);
            $a = $attStmt->fetch(PDO::FETCH_ASSOC);

            $row = [
                'id' => $a['id'] ?? '',
                'user_id' => $worker['user_id'],
                'full_name' => $worker['full_name'],
                'department' => $worker['department'],
                'date' => $dateVal,
                'group_name' => $worker['group_name'] ?? null,
                'check_in' => $a['checkin_time'] ?? null,
                'check_out' => $a['checkout_time'] ?? null,
                'total_hours' => isset($a['checkin_time'], $a['checkout_time']) ? (int)((strtotime($a['checkout_time']) - strtotime($a['checkin_time']))/3600) : null,
                'status' => !$a ? 'Absent' : ($a['checkin_time'] && !$a['checkout_time'] ? 'Checked In' : 'Present')
            ];

            // Apply search filter (username, full_name, department)
            if ($search) {
                $searchLower = mb_strtolower($search);
                $username = isset($worker['username']) ? mb_strtolower($worker['username']) : '';
                $fullName = mb_strtolower($worker['full_name']);
                $departmentName = mb_strtolower($worker['department']);
                if (
                    strpos($username, $searchLower) === false &&
                    strpos($fullName, $searchLower) === false &&
                    strpos($departmentName, $searchLower) === false
                ) {
                    continue;
                }
            }
            // Apply department filter
            if ($department !== 'all' && $worker['department'] !== $department) {
                continue;
            }
            // Apply date filter
            if ($date && $dateVal !== $date) {
                continue;
            }

            $results[] = $row;
        }
    }

    // Sort by date desc, then full_name asc
    usort($results, function($a, $b) {
        if ($a['date'] === $b['date']) {
            return strcmp($a['full_name'], $b['full_name']);
        }
        return strcmp($b['date'], $a['date']);
    });

    return $results;
}

    // 6. Get attendance by date (admin filter)
    public static function getByDate($date) {
         $db = Database::getConnection();

    $stmt = $db->prepare("
        SELECT a.id,
               f.full_name,
               d.name AS department,
               a.checkin_time AS check_in,
               a.checkout_time AS check_out,
               TIMESTAMPDIFF(HOUR, a.checkin_time, a.checkout_time) AS total_hours,
               a.status,
               u.username
        FROM attendance a
        JOIN users u ON a.user_id = u.id
        JOIN field_worker_profiles f ON f.user_id = u.id
        JOIN departments d ON f.department_id = d.id
        WHERE DATE(a.checkin_time) = ?
        ORDER BY a.checkin_time ASC
    ");

    $stmt->execute([$date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}