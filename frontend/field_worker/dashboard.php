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
if ($user && ($user['password'] === 'ttcl123' || $user['password'] === 't@t1234')) {
    header('Location: complete_password.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entry-Log System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script>
        window.USER_ID = <?php echo json_encode($userId); ?>;
        window.DEPARTMENT_ID = <?php echo json_encode($profile['department_id']); ?>;
    </script>
</head>
<body>
    <div class="dashboard">
        <header>
            <div class="header-left">
                <h1><i class="fas fa-clock"></i> Entry-Log System</h1>
                <p>Field Worker Dashboard</p>
            </div>
            <div class="user-info">
                <span><?php echo htmlspecialchars($profile['full_name']); ?></span>
                <span><?php echo htmlspecialchars($profile['department_name']); ?></span>
                <a href="../../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>

        <div class="container">
            <div class="card profile">
                <h2><i class="fas fa-user"></i> Profile Information</h2>
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($profile['full_name']); ?></p>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($profile['department_name']); ?></p>
                <p><strong>Work Group:</strong> <?php echo htmlspecialchars($profile['group_name']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($profile['phone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($profile['email']); ?></p>
                <p><strong>Work Period:</strong> <?php echo htmlspecialchars($profile['start_date']); ?> - <?php echo htmlspecialchars($profile['end_date']); ?></p>
            </div>

            <div class="card attendance">
                <h2><i class="fas fa-clipboard-list"></i> Attendance Actions</h2>
                <p>Manage your daily check-in and check-out</p>
                <button>Check In</button>
                <button>Check Out</button>
            </div>

            <div class="card documentation">
                <h2><i class="fas fa-file-alt"></i> Documentation</h2>
                <div class="deadline-reminder"></div>
                <p>Download templates and upload completed documents</p>
                <div class="download-templates"></div>
                <p>Upload Completed Documents:</p>
                <form class="upload-form" data-type="certificate" enctype="multipart/form-data" style="margin-bottom:10px;">
                    <input type="file" name="certificate_file" accept=".pdf,.doc,.docx" required>
                    <button type="submit"><i class="fas fa-upload"></i> Upload Certificate</button>
                </form>
                <form class="upload-form" data-type="registration" enctype="multipart/form-data">
                    <input type="file" name="registration_file" accept=".pdf,.doc,.docx" required>
                    <button type="submit"><i class="fas fa-upload"></i> Upload Registration</button>
                </form>
            </div>

            <div class="card announcements">
                <h2><i class="fas fa-bullhorn"></i> Daily Announcements</h2>
                <!-- Announcements will be loaded here by JS -->
            </div>

            <div class="card status">
                <h2><i class="fas fa-hourglass-half"></i> Current Status</h2>
                <p></p>
                <p>Status: <span class="status-text"></span></p>
            </div>
        </div>
    </div>
    <script>
    </script>
    <script src="documentation.js"></script>
    <script src="announcements.js"></script>
    <script src="status.js"></script>
    <script src="attendance.js"></script>
</body>
</html>