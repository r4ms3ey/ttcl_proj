
<?php
// FieldWorker model: handles all field worker-related DB operations
require_once __DIR__ . '/../../config/Database.php';

class FieldWorker
{
    // Save profile after first login
    public static function saveProfile($userId, $data) {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "INSERT INTO field_worker_profiles 
            (user_id, full_name, phone, department_id, college, start_date, end_date, email, group_name)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        // Insert profile data into DB
        return $stmt->execute([
            $userId,
            $data['full_name'],
            $data['phone'],
            $data['department_id'],
            $data['college'],
            $data['start_date'],
            $data['end_date'],
            $data['email'],
            $data['group_name']
        ]);
    }

    // Update profile after initial setup
    public static function updateProfile($userId, $data) {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "UPDATE field_worker_profiles
            SET full_name = ?, phone = ?, department_id = ?, college = ?, start_date = ?, end_date = ?, email = ?, group_name = ?
            WHERE user_id = ?"
        );
        // Update profile data in DB
        return $stmt->execute([
            $data['full_name'],
            $data['phone'],
            $data['department_id'],
            $data['college'],
            $data['start_date'],
            $data['end_date'],
            $data['email'],
            $data['group_name'],
            $userId
        ]);
    }

    // Change password for field worker
    public static function updatePassword($userId, $newHash) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$newHash, $userId]); // Update password in DB
    }

    // Get profile details for dashboard display
    public static function getProfile($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "SELECT f.*, d.name AS department_name
            FROM field_worker_profiles f
            JOIN departments d ON f.department_id = d.id
            WHERE f.user_id = ?"
        );
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return profile or null
    }

    // Get group name for group logic
    public static function getGroup($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT group_name FROM field_worker_profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn(); // Return group name
    }

    // Handle document upload (certificate or registration)
    public static function handleUpload($userId, $file, $type) {
        $db = Database::getConnection();

        $filename = $file['name']; // Get uploaded file name
        $path = '../uploads/documents/' . $filename; // Destination path

        // Move uploaded file to destination
        if (!move_uploaded_file($file['tmp_name'], $path)) {
            return ['error' => 'File upload failed.'];
        }

        // Insert or update document record in DB
        $stmt = $db->prepare(
            "INSERT INTO documents (user_id, type, file_name, uploaded_at, status)
            VALUES (?, ?, ?, NOW(), 'uploaded')
            ON DUPLICATE KEY UPDATE
                file_name = VALUES(file_name),
                uploaded_at = NOW(),
                status = 'uploaded'"
        );

        return $stmt->execute([$userId, $type, $filename]); // Return true on success
    }

    // Get all field workers (admin use)
    public static function getAllWorkers() {
        $db = Database::getConnection();
        $stmt = $db->query(
            "SELECT u.id as user_id, u.username, u.role, f.*, d.name AS department_name
            FROM users u
            LEFT JOIN field_worker_profiles f ON u.id = f.user_id
            LEFT JOIN departments d ON f.department_id = d.id
            WHERE u.role = 'field_worker'
            ORDER BY u.id DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all workers
    }

    // Search field workers by search string
    public static function searchWorkers($search) {
        $db = Database::getConnection();
        $query = "
            SELECT u.id as user_id, u.username, u.role, f.*, d.name AS department_name
            FROM users u
            LEFT JOIN field_worker_profiles f ON u.id = f.user_id
            LEFT JOIN departments d ON f.department_id = d.id
            WHERE u.role = 'field_worker' AND (u.username LIKE ? OR f.full_name LIKE ? OR f.email LIKE ? OR d.name LIKE ?)
            ORDER BY u.id DESC
        ";
        $stmt = $db->prepare($query);
        $like = "%$search%";
        $stmt->execute([$like, $like, $like, $like]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return matching workers
    }

    // Delete field worker by user ID
    public static function deleteById($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM field_worker_profiles WHERE user_id = ?");
        $stmt->execute([$userId]); // Delete profile
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$userId]); // Delete user
    }
}


