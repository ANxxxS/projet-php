<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['email'])){
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

require("DAO.php");

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if (!$post_id) {
    echo json_encode(['status' => 'error', 'message' => 'Post ID is required']);
    exit;
}

$sql = "SELECT c.comment_id, c.comment_text, c.comment_date, 
               u.id as user_id, u.nom, u.prenom, u.photo_name
        FROM comments c
        JOIN formule u ON c.user_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.comment_date ASC";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $comments = [];
    
    while($row = mysqli_fetch_assoc($result)) {
        $comment = [
            'id' => $row['comment_id'],
            'text' => $row['comment_text'],
            'date' => $row['comment_date'],
            'user' => [
                'id' => $row['user_id'],
                'name' => $row['nom'] . ' ' . $row['prenom'],
                'photo' => $row['photo_name']
            ]
        ];
        
        $comments[] = $comment;
    }
    
    echo json_encode(['status' => 'success', 'comments' => $comments]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . mysqli_error($con)]);
}

mysqli_close($con);
?>