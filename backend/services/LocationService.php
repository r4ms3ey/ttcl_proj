<?php

require_once __DIR__ . '/../../config/Database.php';

class LocationService
{
    /**
     * Check if the user's coordinates are within the allowed location radius (meters)
     * @param float $userLat
     * @param float $userLng
     * @param float $radiusMeters
     * @return bool
     */
    public static function isWithinAllowedLocation($userLat, $userLng, $radiusMeters = 100) {
        require_once __DIR__ . '/../models/Settings.php';
        $locationJson = Settings::get('attendance_location');
        if (!$locationJson) return false;
        $location = json_decode($locationJson, true);
        if (!isset($location['latitude'], $location['longitude'])) return false;
        $allowedLat = floatval($location['latitude']);
        $allowedLng = floatval($location['longitude']);
        // Haversine formula
        $earthRadius = 6371000; // meters
        $dLat = deg2rad($userLat - $allowedLat);
        $dLng = deg2rad($userLng - $allowedLng);
        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($allowedLat)) * cos(deg2rad($userLat)) *
            sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        return $distance <= $radiusMeters;
    }
    /**
     * Check if the user's IP is allowed for check-in/check-out
     *
     * @param string|null $ip
     * @return bool
     */
    public static function isAllowedIP(?string $ip): bool
    {
        if (!$ip) return false;

        $db = Database::getConnection();

        // Assuming you have a table locations with columns: id, name, ip_address
        $stmt = $db->prepare("SELECT COUNT(*) FROM locations WHERE ip_address = ?");
        $stmt->execute([$ip]);

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Get location info by IP
     *
     * @param string|null $ip
     * @return array|null
     */
    public static function getLocationByIP(?string $ip): ?array
    {
        if (!$ip) return null;

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM locations WHERE ip_address = ?");
        $stmt->execute([$ip]);
        $location = $stmt->fetch(PDO::FETCH_ASSOC);

        return $location ?: null;
    }
}