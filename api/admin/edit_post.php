<?php
session_start();
include('../includes/db.php');
include('auth_check.php'); // Ensure only admins can access

// Check if post ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid post ID.";
    header("Location: manage_posts.php");
    exit();
}

$id = intval($_GET['id']); // Get post ID
$post_query = "SELECT * FROM posts WHERE id = $id";
$result = mysqli_query($conn, $post_query);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    $_SESSION['error'] = "Post not found.";
    header("Location: manage_posts.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image = $post['image']; // Keep old image unless changed

    // Handle image upload if a new image is provided
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $image = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image;

        // Validate and move uploaded file
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types) && move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Delete old image if a new one is uploaded
            if (!empty($post['image']) && file_exists($target_dir . $post['image'])) {
                unlink($target_dir . $post['image']);
            }
        } else {
            $_SESSION['error'] = "Invalid image file.";
            header("Location: edit_post.php?id=$id");
            exit();
        }
    }

    // Update post details in the database
    $update_sql = "UPDATE posts SET title='$title', content='$content', image='$image' WHERE id=$id";
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['message'] = "Post updated successfully!";
        header("Location: manage_posts.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating post: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Post</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="edit_post.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <?php if (!empty($post['image'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($post['image']); ?>" width="200">
                <?php else: ?>
                    <p>No image uploaded</p>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload New Image (Optional)</label>
                <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Update Post</button>
            <a href="manage_posts.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>