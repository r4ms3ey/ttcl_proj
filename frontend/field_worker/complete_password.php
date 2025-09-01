<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../backend/models/FieldWorker.php';
require_once __DIR__ . '/../../backend/models/User.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'field_worker') {
    header('Location: ../../index.php');
    exit;
}

$userId = $_SESSION['user_id'];
$profile = FieldWorker::getProfile($userId);
if (!$profile) {
    header('Location: complete_profile.php');
    exit;
}
$user = User::findById($userId);
if ($user && $user['password'] !== 'ttcl123' && $user['password'] !== 't@t1234') {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile</title>
    <link rel="stylesheet" href="password.css">
    <script src="password.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <div class="header">
                <h2>Complete Your Profile</h2>
                <span class="close-btn">×</span>
            </div>
            <p class="instruction">Please fill in your details and change your default password to continue.</p>
            <div class="tabs">
                <button class="tab">Personal Details</button>
                <button class="tab active">Change Password</button>
            </div>
            <div class="password-info">
                <h3>Change Default Password</h3>
                <p>For security, you must change your default password</p>
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" id="current_password" placeholder="Enter current password (t@t1234)">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" id="new_password" placeholder="Enter new password (min 6 characters)">
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" id="confirm_password" placeholder="Confirm new password">
                </div>
                <button type="button" class="submit-btn" id="submitPassword">Complete Setup</button>
            </div>
        </div>
    </div>

</body>
</html>