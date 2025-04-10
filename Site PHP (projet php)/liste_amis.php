<?php
session_start();
include("DAO.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT i.invitation_id, i.sender_id, i.receiver_id, u.id, u.nom, u.prenom, u.photo_name
        FROM invitation i
        JOIN formule u ON (i.sender_id = u.id AND i.receiver_id = '$user_id') 
                      OR (i.receiver_id = u.id AND i.sender_id = '$user_id')
        WHERE i.status = 'accepted'
        ORDER BY i.invitation_id DESC";

$result = mysqli_query($con, $sql);

$amis = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['sender_id'] == $user_id) {
            $ami_id = $row['receiver_id'];

            $sql2 = "SELECT id, nom, prenom, photo_name FROM formule WHERE id = '$ami_id'";
            $result2 = mysqli_query($con, $sql2);

            if ($ami_data = mysqli_fetch_assoc($result2)) {
                $amis[] = [
                    'id' => $ami_data['id'],
                    'nom' => $ami_data['nom'],
                    'prenom' => $ami_data['prenom'],
                    'photo_name' => $ami_data['photo_name']
                ];
            }
        } else {
            $amis[] = [
                'id' => $row['id'],
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'photo_name' => $row['photo_name']
            ];
        }
    }
}

echo json_encode($amis);
mysqli_close($con);
