<?php
session_start();
include('DAO.php');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

$ami_id = isset($_GET['ami_id']) ? intval($_GET['ami_id']) : 0;

if ($ami_id <= 0) {
    echo json_encode(['error' => 'ID ami invalide']);
    exit;
}

$user_id = $_SESSION['user_id'];
$user_id_safe = intval($user_id);
$ami_id_safe = intval($ami_id);

$query = "SELECT m.id, m.expediteur_id, m.destinataire_id, m.contenu, m.date_envoi, 
          (m.expediteur_id = $user_id_safe) as est_expediteur
          FROM meessages m
          WHERE (m.expediteur_id = $user_id_safe AND m.destinataire_id = $ami_id_safe)
          OR (m.expediteur_id = $ami_id_safe AND m.destinataire_id = $user_id_safe)
          ORDER BY m.date_envoi ASC";

$result = mysqli_query($con, $query); 

if (!$result) {
    echo json_encode(['error' => 'Erreur lors de la récupération des messages: ' . mysqli_error($con)]);
    exit;
}

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $date = new DateTime($row['date_envoi']);
    $row['date_envoi'] = $date->format('d/m/Y H:i');
    
    $row['est_expediteur'] = (bool)$row['est_expediteur'];
    
    $messages[] = $row;
}

mysqli_free_result($result);
mysqli_close($con); 

echo json_encode($messages);
?>