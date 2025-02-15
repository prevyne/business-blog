<?php
session_start();
include('../includes/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the user's information
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-actions">
            <a href="../posts/view_post.php" class="btn">View Posts</a>
            <a href="profile.php" class="btn">Edit Profile</a>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
    <div>
        <h2>Welcome, <?php echo $user['name']; ?>!</h2>
        <p>Here is your dashboard. You can view posts and manage your profile.</p>
    </div>
    <div class="profile-info">
            <h4>Your Profile</h4>
            <strong><p>Name:</strong> <?php echo $user['name']; ?></p>
            <strong><p>Email:</strong> <?php echo $user['email']; ?></p>
        </div>
</body>
</html>
