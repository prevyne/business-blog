<?php
session_start();
include('../includes/db.php');
include('../includes/mailer.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));

    $stmt = $conn->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
    $stmt->bind_param("ss", $token, $email);
    
    if ($stmt->execute()) {
        sendPasswordResetEmail($email, $token);
        $_SESSION['message'] = "Check your email for the reset link.";
        header("Location: login.php");
    } else {
        $_SESSION['error'] = "Something went wrong!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
