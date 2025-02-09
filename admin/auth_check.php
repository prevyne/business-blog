<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debugging: Check if session values exist
if (!isset($_SESSION['user_id'])) {
    error_log("Session user_id is not set");
}
if (!isset($_SESSION['role'])) {
    error_log("Session role is not set");
}

// Ensure user is logged in
if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
    $_SESSION['error'] = "You must be logged in to access this page.";
    header("Location: login.php");
    exit();
}

// Ensure only admins can access
if ($_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Unauthorized access.";
    header("Location: ../index.php"); // Redirect non-admin users
    exit();
}

// Regenerate session ID for security (only once per session)
if (!isset($_SESSION['session_regenerated'])) {
    session_regenerate_id(true);
    $_SESSION['session_regenerated'] = true;
}

// Debugging: Log successful authentication
error_log("User authenticated: " . $_SESSION['user_id'] . " | Role: " . $_SESSION['role']);
?>