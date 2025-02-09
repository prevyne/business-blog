<?php
//session_start();
include('../includes/db.php');
include('auth_check.php'); // Ensure only admins can access

// Fetch all posts
$posts_sql = "SELECT posts.id, posts.title, posts.image, posts.created_at, users.name AS author 
              FROM posts 
              JOIN users ON posts.user_id = users.id 
              ORDER BY posts.created_at DESC";
$posts_result = $conn->query($posts_sql);

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // First, get the image filename to delete it
    $image_sql = "SELECT image FROM posts WHERE id = ?";
    $stmt = $conn->prepare($image_sql);
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    // Delete post from database
    $delete_sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $delete_id);
    
    if ($stmt->execute()) {
        // Delete the image file if it exists
        if (!empty($image) && file_exists("../uploads/" . $image)) {
            unlink("../uploads/" . $image);
        }
        $_SESSION['message'] = "Post deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete post.";
    }

    $stmt->close();
    header("Location: manage_posts.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Posts</h2>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <a href="create_post.php" class="btn btn-primary mb-3">Create New Post</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Image</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($post = $posts_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['author']); ?></td>
                        <td>
                            <?php if (!empty($post['image'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" width="50">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?php echo date("d M Y, H:i", strtotime($post['created_at'])); ?></td>
                        <td>
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete_id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
