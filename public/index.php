<?php
session_start();
include('../includes/db.php');

// Fetch the latest posts
$posts_sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 5";
$posts_result = $conn->query($posts_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Blog</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Include header -->
     <div>
        <?php include('../includes/header.php'); ?>
     </div>

     <div>
     <h2>Welcome to Untold Story Graphics</h2>
     <p>Explore our latest blog posts below:</p>
     </div>
    
    <div class="container mt-5">
      <!-- Display Posts -->
        <div class="posts-list">
            <?php while ($post = $posts_result->fetch_assoc()): ?>
                <div class="post-item">
                    <h3><a href="../posts/view_post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                    <p><?php echo htmlspecialchars(substr($post['content'], 0, 150)) . '...'; ?></p>
                    <p><small>Posted on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></small></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

        <!-- Authentication Links -->
        <div class="auth-links mt-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Welcome, <strong><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?></strong>!</p>
                <a href="../user/dashboard.php" class="btn btn-info">Dashboard</a>
                <a href="../user/logout.php" class="btn btn-secondary">Logout</a>
            <?php else: ?>
                <a href="../user/login.php" class="btn btn-primary">Login</a>
                <a href="../user/signup.php" class="btn btn-success">Sign Up</a>
            <?php endif; ?>
        </div>

    

    <!-- Include footer -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>
