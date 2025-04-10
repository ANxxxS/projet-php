<?php
session_start();
include('DAO.php');
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'Non authentifié']));
}

$ami_id = isset($_GET['ami_id']) ? intval($_GET['ami_id']) : 0;
$dernier_id = isset($_GET['dernier_id']) ? intval($_GET['dernier_id']) : 0;

if ($ami_id < 1) {
    die(json_encode(['error' => 'ID ami invalide']));
}

$user_id = $_SESSION['user_id'];

$query = "SELECT m.id, m.expediteur_id, m.contenu, m.date_envoi, 
          (m.expediteur_id = ?) AS est_expediteur
          FROM messages m
          WHERE m.destinataire_id = ? 
          AND m.expediteur_id = ?
          AND m.id > ?
          ORDER BY m.date_envoi ASC";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "iiii", $user_id, $ami_id, $dernier_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['date_envoi'] = (new DateTime($row['date_envoi']))->format('H:i');
    $row['est_expediteur'] = (bool)$row['est_expediteur'];
    $messages[] = $row;
}

echo json_encode($messages);
mysqli_stmt_close($stmt);
?>