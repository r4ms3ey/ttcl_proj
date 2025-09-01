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
if ($profile) {
    // If profile is already complete, go to password step if needed
    $user = User::findById($userId);
    if ($user && ($user['password'] === 'ttcl123' || $user['password'] === 't@t1234')) {
        header('Location: complete_password.php');
        exit;
    }
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
    <link rel="stylesheet" href="profile.css">
    <script src="profile.js" defer></script>
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
                <button class="tab active">Personal Details</button>
                <button class="tab">Change Password</button>
            </div>
            <div class="personal-info">
                <div class="form-group row">
                    <div class="col">
                        <label>Full Name *</label>
                        <input type="text" id="full_name" placeholder="Enter your full name">
                    </div>
                    <div class="col">
                        <label>Phone Number *</label>
                        <input type="text" id="phone" value="+1234567890">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <label>Department *</label>
                        <select id="department">
                            <option value="" selected>Select department</option>
                        </select>
                    </div>
                    <div class="col">
                        <label>Group</label>
                        <select id="group_name">
                            <option value="A">Group A</option>
                            <option value="B">Group B</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>College/University Name</label>
                    <input type="text" id="college" placeholder="Enter your institution name">
                </div>
                <div class="form-group row">
                    <div class="col">
                        <label>Start Date</label>
                        <input type="date" id="start_date">
                    </div>
                    <div class="col">
                        <label>End Date</label>
                        <input type="date" id="end_date">
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" id="email" value="your.email@example.com">
                </div>
                <button type="button" class="submit-btn" id="submitProfile">Continue to Password Change</button>
            </div>
        </div>
    </div>
    
</body>
</html>