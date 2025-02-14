<?php
// Include necessary files
include('../includes/db.php');  // Include database connection
include('../includes/header.php');  // Include header for navigation

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the DELETE SQL query
    $sql = "DELETE FROM posts WHERE id = $id";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Post successfully deleted, redirect to the homepage
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}

include('../includes/footer.php');  // Include footer
?>