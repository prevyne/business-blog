<?php
include('../includes/header.php');
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Handle the file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageSize = $_FILES['image']['size'];
        $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);
        
        // Check if the file is an image
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($imageExt), $allowedExtensions)) {
            // Generate a unique name for the image and move it to the uploads directory
            $newImageName = uniqid('', true) . '.' . $imageExt;
            $uploadDir = '../uploads/';
            $uploadFile = $uploadDir . $newImageName;

            if (move_uploaded_file($imageTmpName, $uploadFile)) {
                // Insert post with image path into the database
                $sql = "INSERT INTO posts (title, content, image) VALUES ('$title', '$content', '$newImageName')";
                if (mysqli_query($conn, $sql)) {
                    echo "New post created successfully.";
                    header("Location: ../index.php");
                    exit();
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Failed to upload image.";
            }
        } else {
            echo "Only image files (JPG, JPEG, PNG, GIF) are allowed.";
        }
    } else {
        echo "No image uploaded or there was an error.";
    }
}
?>

<h1>Create a New Post</h1>
<form method="POST" action="create.php" enctype="multipart/form-data">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required><br><br>
    
    <label for="content">Content:</label>
    <textarea name="content" id="content" required></textarea><br><br>
    
    <label for="image">Image:</label>
    <input type="file" name="image" id="image" accept="image/*"><br><br>

    <input type="submit" value="Create Post">
</form>

<?php include('../includes/footer.php'); ?>