<?php
session_start();
include('../includes/db.php');
include('auth_check.php');  // Ensure the user is an admin

// Fetch all posts from the database
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
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

        <!-- Add New Post Button -->
        <a href="create_post.php" class="btn btn-success mb-3">Create New Post</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are posts
                if ($result->num_rows > 0) {
                    while ($post = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $post['id'] . "</td>
                            <td>" . htmlspecialchars($post['title']) . "</td>
                            <td>" . $post['created_at'] . "</td>
                            <td>
                                <a href='edit_post.php?id=" . $post['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_post.php?id=" . $post['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this post?\");'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No posts found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
