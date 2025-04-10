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


    
        $query = "UPDATE invitation SET status = 'rejected' WHERE invitation_id = '$invitation_id' AND receiver_id = '$user_id' AND status = 'pending'";
        $result = mysqli_query($con, $query);
        if(mysqli_affected_rows($con) > 0) {
      
        mysqli_close($con);
        header("Location: Invitation.php?message=Invitation rejected avec succès");
    } else {
         mysqli_close($con);
         header("Location: Invitation.php?errors=Impossible de rejected cette invitation");
    }
    
    exit();

   
?>