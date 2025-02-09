<?php

include('../includes/db.php');
include('auth_check.php'); // This ensures only admins can access

echo "Welcome, " . htmlspecialchars($_SESSION['admin_name']);

// Fetch statistics
$total_users_sql = "SELECT COUNT(*) AS total_users FROM users";
$total_admins_sql = "SELECT COUNT(*) AS total_admins FROM users WHERE role = 'admin'";

// Get user statistics
$total_users_result = $conn->query($total_users_sql);
$total_users = $total_users_result->fetch_assoc()['total_users'];

$total_admins_result = $conn->query($total_admins_sql);
$total_admins = $total_admins_result->fetch_assoc()['total_admins'];

// Fetch latest users
$latest_users_sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT 5";
$latest_users_result = $conn->query($latest_users_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Admin Dashboard</h2>

        <?php if (isset($_SESSION['admin_name'])): ?>
            <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong></p>
        <?php endif; ?>

        <div class="row">
            <!-- Total Users Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $total_users; ?> Users</p>
                    </div>
                </div>
            </div>

            <!-- Total Admins Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Admins</h5>
                        <p class="card-text"><?php echo $total_admins; ?> Admins</p>
                    </div>
                </div>
            </div>

            <!-- Latest Users Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Latest Users</h5>
                        <ul class="list-group">
                            <?php while ($user = $latest_users_result->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <?php echo htmlspecialchars($user['name']); ?> (<?php echo htmlspecialchars($user['email']); ?>)
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <a href="manage_users.php" class="btn btn-primary btn-block">Manage Users</a>
            </div>
            <div class="col-md-4">
                <a href="manage_posts.php" class="btn btn-success btn-block">Manage Posts</a>
            </div>
        </div>

        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
