<?php
session_start();
include('../includes/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Update user information
    $update_sql = "UPDATE users SET name = '$name', email = '$email' WHERE id = '$user_id'";
    if ($conn->query($update_sql)) {
        $_SESSION['message'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        $error = "Error updating profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>

        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>

        <form action="profile.php" method="POST">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" value="<?php echo $user['name']; ?>" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>