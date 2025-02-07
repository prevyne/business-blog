<?php
include('../includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ?, password_reset_token = NULL WHERE password_reset_token = ?");
    $stmt->bind_param("ss", $new_password, $token);
    
    if ($stmt->execute()) {
        echo "Password reset successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Invalid token!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Set New Password</h2>
    <form method="POST">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <input type="password" name="password" placeholder="New Password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
