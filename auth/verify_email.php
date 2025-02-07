<?php
include('../includes/db.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $update = $conn->prepare("UPDATE users SET email_verified = 1 WHERE verification_token = ?");
        $update->bind_param("s", $token);
        $update->execute();
        echo "Email verified successfully. <a href='login.php'>Login here</a>";
    } else {
        echo "Invalid token!";
    }
}
?>