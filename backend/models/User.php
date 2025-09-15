
<?php
// User model: handles all user-related DB operations
require_once __DIR__ . '/../../config/Database.php';

class User {

    // Find a user by username
    public static function findByUsername($username) {
        $db = Database::getConnection(); // Get DB connection
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]); // Execute query
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return user record or null
    }

    // Authenticate user with plain text password (not secure)
    public static function login($username, $password) {
        $user = self::findByUsername($username); // Get user by username

        // Check if password matches
        if ($user && $password === $user['password']) {
            return $user; // Login successful
        }
        return false; // Invalid credentials
    }

    // Check if a username already exists
    public static function usernameExists($username) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0; // Return true if exists
    }

    // Create a new user record
    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        return $stmt->execute([
            $data['username'],
            $data['password'], // Store plain text (not secure)
            $data['role']
        ]); // Return true on success
    }

    // Update a user's password
    public static function updatePassword($userId, $newPassword) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$newPassword, $userId]); // Return true on success
    }

    // Find a user by ID
    public static function findById($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return user record or null
    }
}
