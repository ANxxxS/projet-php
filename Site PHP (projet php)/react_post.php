<?php
session_start();
if(!isset($_SESSION['email'])){
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

require("DAO.php");

$user_id = $_SESSION['user_id'];
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
$reaction_type = isset($_POST['reaction_type']) ? $_POST['reaction_type'] : '';

if (!$post_id || !in_array($reaction_type, ['like', 'dislike'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid post ID or reaction type']);
    exit;
}

$check_sql = "SELECT reaction_id, reaction_type FROM reactions 
              WHERE user_id = '$user_id' AND post_id = '$post_id'";
$check_result = mysqli_query($con, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    $row = mysqli_fetch_assoc($check_result);
    $existing_reaction = $row['reaction_type'];
    $reaction_id = $row['reaction_id'];
    
    if ($existing_reaction == $reaction_type) {
        $sql = "DELETE FROM reactions WHERE reaction_id = '$reaction_id'";
        
        if ($reaction_type == 'like') {
            $update_sql = "UPDATE posts SET nblike = nblike - 1 WHERE idpost = '$post_id'";
        } else {
            $update_sql = "UPDATE posts SET nbdeslike = nbdeslike - 1 WHERE idpost = '$post_id'";
        }
        
        mysqli_query($con, $update_sql);
    } else {
        $sql = "UPDATE reactions SET reaction_type = '$reaction_type' WHERE reaction_id = '$reaction_id'";
        
        if ($reaction_type == 'like') {
            $update_sql = "UPDATE posts SET nblike = nblike + 1, nbdeslike = nbdeslike - 1 WHERE idpost = '$post_id'";
        } else {
            $update_sql = "UPDATE posts SET nblike = nblike - 1, nbdeslike = nbdeslike + 1 WHERE idpost = '$post_id'";
        }
        
        mysqli_query($con, $update_sql);
    }
} else {
    $sql = "INSERT INTO reactions (user_id, post_id, reaction_type, reaction_date) 
            VALUES ('$user_id', '$post_id', '$reaction_type', NOW())";
    
    if ($reaction_type == 'like') {
        $update_sql = "UPDATE posts SET nblike = nblike + 1 WHERE idpost = '$post_id'";
    } else {
        $update_sql = "UPDATE posts SET nbdeslike = nbdeslike + 1 WHERE idpost = '$post_id'";
    }
    
    mysqli_query($con, $update_sql);
}

if (mysqli_query($con, $sql)) {
    $get_post_sql = "SELECT nblike, nbdeslike FROM posts WHERE idpost = '$post_id'";
    $post_result = mysqli_query($con, $get_post_sql);
    $post_data = mysqli_fetch_assoc($post_result);
    
    echo json_encode([
        'status' => 'success', 
        'likes' => $post_data['nblike'], 
        'dislikes' => $post_data['nbdeslike']
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . mysqli_error($con)]);
}

mysqli_close($con);
?>