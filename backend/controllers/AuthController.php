
<?php
// Include the User model for authentication logic
require_once __DIR__ . '/../models/User.php';

// Controller for authentication (login/logout)
class AuthController {

    // Handles user login
    public static function login($username, $password) {
        // Attempt to authenticate user with provided credentials
        $user = User::login($username, $password); // Checks username and password

        if ($user) {
            // If authentication succeeds, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            session_regenerate_id(true); // Prevent session fixation

            // Redirect user based on role
            if ($user['role'] === 'admin') {
                // Admins go to admin dashboard
                header('Location: /ttcl_proj/frontend/admin/dashboard.php');
            } else {
                // Field workers go to onboarding redirector
                header('Location: /ttcl_proj/frontend/field_worker/auth_redirect.php');
            }
            exit;
        } else {
            // If authentication fails, set error and redirect to login
            $_SESSION['error'] = 'Invalid credentials';
            header('Location: /ttcl_proj/index.php');
            exit;
        }
    }

    // Handles user logout
    public static function logout() {
        session_start(); // Start session if not already started
        session_unset(); // Remove all session variables
        session_destroy(); // Destroy session
        header('Location: /ttcl_proj/index.php'); // Redirect to login page
        exit;
    }
}
