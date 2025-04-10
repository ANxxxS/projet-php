<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['email'])){
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

require("DAO.php");

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User ID not found in session']);
    exit;
}

$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
$comment_text = isset($_POST['comment_text']) ? trim($_POST['comment_text']) : '';

if (!$post_id) {
    echo json_encode(['status' => 'error', 'message' => 'Post ID is required']);
    exit;
}

if (empty($comment_text)) {
    echo json_encode(['status' => 'error', 'message' => 'Comment text is required']);
    exit;
}

$sql = "INSERT INTO comments (post_id, user_id, comment_text, comment_date) VALUES (?, ?, ?, NOW())";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "iis", $post_id, $user_id, $comment_text);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Comment added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . mysqli_error($con)]);
}

mysqli_close($con);
?>