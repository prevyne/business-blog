<?php
session_start();
include('../includes/db.php');
include('../includes/header.php');

// Validate the post ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid post ID.";
    header("Location: ../public/index.php");
    exit();
}

$id = intval($_GET['id']); // Sanitize post ID

// Fetch post details
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    $_SESSION['error'] = "Post not found.";
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4"><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="text-muted">Published on <?php echo date("F j, Y", strtotime($post['created_at'])); ?></p>

        <?php if (!empty($post['image'])): ?>
            <img src="../uploads/<?php echo htmlspecialchars($post['image']); ?>" class="img-fluid mb-3" alt="Post Image">
        <?php endif; ?>

        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

        <a href="../public/index.php" class="btn btn-primary mt-3">Back to Home</a>
    </div>
</body>
</html>
