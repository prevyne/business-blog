<?php
session_start();
include('../includes/db.php');
include('auth_check.php'); // Ensure only admins can access

// Check if a post ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer

    // Fetch post details to get image name
    $sql = "SELECT image FROM posts WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);

    if ($post) {
        // Delete associated image file if it exists
        if (!empty($post['image'])) {
            $image_path = "../uploads/" . $post['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Delete post from database
        $delete_sql = "DELETE FROM posts WHERE id = $id";
        if (mysqli_query($conn, $delete_sql)) {
            $_SESSION['message'] = "Post deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting post: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Post not found.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

// Redirect to manage posts page
header("Location: manage_posts.php");
exit();
?>