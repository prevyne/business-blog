<?php
include('../includes/db.php');
include('../includes/header.php');

// Fetch all posts from the database
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>All Posts</h1>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="post-list">
                <?php while ($post = mysqli_fetch_assoc($result)): ?>
                    <div class="post-item">
                        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                        <p><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 150))) . '...'; ?></p>
                        <?php if (!empty($post['image'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" width="150">
                        <?php endif; ?>
                        <br>
                        <a href="view_post.php?id=<?php echo urlencode($post['id']); ?>" class="btn btn-primary">Read More</a>
                    </div>
                    <hr>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </div>
</body>
</html>