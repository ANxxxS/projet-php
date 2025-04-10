<?php
session_start();
include("DAO.php");
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: Invitation.php");
    exit;
}
$invitation_id =$_GET['id'];

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Vous devez être connecté");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql1 ="SELECT `sender_id` FROM `invitation` WHERE `invitation_id` = '$invitation_id'";
$result = mysqli_query($con,$sql1);
if (mysqli_num_rows($result) > 0) {
    $DATA = mysqli_fetch_assoc($result);
   $id2= $DATA['sender_id'];

    $sql="INSERT INTO `amis` (`user_id1`, `user_id2`, `created_at`) VALUES ('$id2', '$user_id', current_timestamp())";

    $result = mysqli_query($con,$sql);
    if(mysqli_affected_rows($con) > 0) {
    
        $query = "UPDATE invitation SET status = 'accepted' WHERE invitation_id = '$invitation_id' AND receiver_id = '$user_id' AND status = 'pending'";
        $result = mysqli_query($con, $query);
        if(mysqli_affected_rows($con) > 0) {
      
        mysqli_close($con);
        header("Location: Invitation.php?message=Invitation acceptée avec succès");
    } else {
         mysqli_close($con);
         header("Location: Invitation.php?errors=Impossible d'accepter cette invitation");
    }}
    
    exit();
}
   
?>