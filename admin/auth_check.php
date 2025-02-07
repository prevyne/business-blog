<?php
session_start();

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>