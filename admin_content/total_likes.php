<?php
// Include the database connection file
include '../components/connect.php';

$db = new Database();
$conn = $db->connect();

// Start the session
session_start();

// Check if the admin is logged in
$admin_id = $_SESSION['admin_id'] ?? null;

if (!isset($admin_id)) {
    header('location:../admin/admin_login.php'); // Redirect to admin login page if not logged in
    exit();
}

// Fetch published posts created by the logged-in admin
$select_posts = $conn->prepare("
    SELECT posts.post_id, posts.title, COUNT(likes.like_id) AS total_likes 
    FROM `posts` 
    LEFT JOIN `likes` ON posts.post_id = likes.post_id 
    WHERE posts.created_by = ? AND posts.status = 'published' 
    GROUP BY posts.post_id
");
$select_posts->execute([$admin_id]);
$posts = $select_posts->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Likes</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS File -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/header.php'; ?>

<section class="likes-container">
    <h1 class="heading">Total Likes on Your Published Posts</h1>

    <div class="box-container">
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="box">
                    <p>Post Title: <span><?= $post['title']; ?></span></p>
                    <p>Total Likes: <span><?= $post['total_likes']; ?></span></p>
                    <a href="view_post.php?post_id=<?= $post['post_id']; ?>" class="btn">View Post</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">You have no published posts yet!</p>
        <?php endif; ?>
    </div>
</section>

<!-- Custom JS File -->
<script src="../js/admin_script.js"></script>
</body>
</html>