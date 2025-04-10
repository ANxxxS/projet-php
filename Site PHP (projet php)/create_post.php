<?php
session_start();
if(!isset($_SESSION['email'])){
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

require("DAO.php");

$user_id = $_SESSION['user_id'];

$caption = isset($_POST['caption']) ? $_POST['caption'] : '';

if(!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
    echo json_encode(['status' => 'error', 'message' => 'Image upload is required']);
    exit;
}

if(!is_dir('user_post')) {
    mkdir('user_post', 0777, true);
}

$user_dir = 'user_post/' . $user_id;
if(!is_dir($user_dir)) {
    mkdir($user_dir, 0777, true);
}

$image_name = uniqid() . '_' . $_FILES['image']['name'];
$image_path = $user_dir . '/' . $image_name;

if(move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
    $sql = "INSERT INTO posts (idUser, text, imgpost, datepost) VALUES ('$user_id', '$caption', '$image_name', NOW())";
    
    if(mysqli_query($con, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Post created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . mysqli_error($con)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
}

mysqli_close($con);
?>