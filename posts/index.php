<?php
include('../includes/db.php');
include('../includes/header.php');

// Fetch all posts
$sql = "SELECT posts.id, posts.title, posts.content, posts.image, posts.created_at, users.name 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Latest Posts</h2>
        <div class="row">
            <?php while ($post = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <?php if (!empty($post['image'])): ?>
                            <img src="../uploads/<?php echo $post['image']; ?>" class="card-img-top" alt="Post Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                            <p class="card-text text-muted">By <?php echo htmlspecialchars($post['name']); ?> | <?php echo date('M d, Y', strtotime($post['created_at'])); ?></p>
                            <p class="card-text"><?php echo substr(htmlspecialchars($post['content']), 0, 100); ?>...</p>
                            <a href="view_post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
