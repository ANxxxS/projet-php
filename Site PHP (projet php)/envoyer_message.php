<?php
session_start();
include('DAO.php');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

if (!isset($_POST['ami_id']) || !isset($_POST['message'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

$ami_id = intval($_POST['ami_id']);
$message = trim($_POST['message']);
$user_id = $_SESSION['user_id'];

if (empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Le message ne peut pas être vide']);
    exit;
}


mysqli_autocommit($con, FALSE);


$message_safe = mysqli_real_escape_string($con, $message);
$user_id_safe = intval($user_id);
$ami_id_safe = intval($ami_id);


$query = "SELECT * FROM amis WHERE 
          (user_id1 = $user_id_safe AND user_id2 = $ami_id_safe) OR 
          (user_id1 = $ami_id_safe AND user_id2 = $user_id_safe)";

$result = mysqli_query($con, $query);
$est_ami = mysqli_num_rows($result) > 0;
mysqli_free_result($result);

if (!$est_ami) {
    mysqli_close($con);
    echo json_encode(['success' => false, 'message' => 'Cet utilisateur n\'est pas dans votre liste d\'amis']);
    exit;
}

$query = "INSERT INTO meessages (expediteur_id, destinataire_id, contenu, date_envoi)
          VALUES ($user_id_safe, $ami_id_safe, '$message_safe', NOW())";

$result = mysqli_query($con, $query);

if ($result) {
    mysqli_commit($con);
    $response = ['success' => true, 'message' => 'Message envoyé avec succès'];
} else {
    mysqli_rollback($con);
    $response = ['success' => false, 'message' => 'Erreur lors de l\'envoi du message: ' . mysqli_error($con)];
}

mysqli_close($con);

echo json_encode($response);
?>