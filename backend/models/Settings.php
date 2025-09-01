<?php
require_once __DIR__ . '/../../config/Database.php';
class Settings {
    public static function set($key, $value) {
        $db = Database::getConnection();
        // Try update first
        $stmt = $db->prepare("UPDATE settings SET value = ? WHERE key_name = ?");
        $stmt->execute([$value, $key]);
        if ($stmt->rowCount() === 0) {
            // Insert if not exists
            $stmt = $db->prepare("INSERT INTO settings (key_name, value) VALUES (?, ?)");
            return $stmt->execute([$key, $value]);
        }
        return true;
    }
    public static function get($key) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT value FROM settings WHERE key_name = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['value'] : null;
    }
}