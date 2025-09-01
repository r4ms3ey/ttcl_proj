<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../backend/models/FieldWorker.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'field_worker') {
    header('Location: ../../index.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Check if profile exists
$profile = FieldWorker::getProfile($userId);
if (!$profile) {
    header('Location: complete_profile.php');
    exit;
}

// Check if password is default (e.g., ttcl123 or t@t1234)
require_once __DIR__ . '/../../backend/models/User.php';
$user = User::findById($userId);
if ($user && ($user['password'] === 'ttcl123' || $user['password'] === 't@t1234')) {
    header('Location: complete_password.php');
    exit;
}

// If both are complete, go to dashboard
header('Location: dashboard.php');
exit;
