<?php
session_start();
if(!isset($_SESSION['email'])){
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

require("DAO.php");

$ido=$_SESSION['user_id'];
$sql = "SELECT p.idpost, p.text, p.imgpost, p.datepost, p.nblike, p.nbdeslike, p.idUser,
        u.id AS user_id, u.nom, u.prenom, u.photo_name 
        FROM posts p 
        JOIN formule u ON p.idUser = u.id 
        WHERE p.idUser = '$ido' 
           OR p.idUser IN (
               SELECT CASE
                   WHEN i.sender_id = '$ido' THEN i.receiver_id
                   ELSE i.sender_id
               END AS friend_id
               FROM invitation i
               WHERE (i.sender_id = '$ido' OR i.receiver_id = '$ido')
               AND i.status = 'accepted'
           )
        ORDER BY p.datepost DESC";

$result = mysqli_query($con, $sql);

if ($result) {
    $posts = [];
    
    while($row = mysqli_fetch_assoc($result)) {
        $post = [
            'id' => $row['idpost'],
            'text' => $row['text'],
            'image' => $row['imgpost'],
            'date' => $row['datepost'],
            'likes' => $row['nblike'],
            'dislikes' => $row['nbdeslike'],
            'user' => [
                'id' => $row['user_id'],
                'name' => $row['nom'] . ' ' . $row['prenom'],
                'photo' => $row['photo_name']
            ]
        ];
        
        $posts[] = $post;
    }
    
    echo json_encode(['status' => 'success', 'posts' => $posts]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . mysqli_error($con)]);
}

mysqli_close($con);
?>