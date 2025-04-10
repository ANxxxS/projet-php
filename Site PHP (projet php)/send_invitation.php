<?php
session_start();

if(!isset($_SESSION['email']) || !isset($_SESSION['user_id'])){
    header("Location: index.html");
    exit;
}

$idSender = $_SESSION['user_id'];
if(!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header("Location: Invitation.php");
    exit;
}
$idReceiver =$_GET['user_id'];

if($idSender == $idReceiver) {
    header("Location: Invitation.php?self_invite=1");
    exit;
}

include("DAO.php");


$check_query = "SELECT * FROM invitation WHERE 
               (sender_id = '$idSender' AND receiver_id = '$idReceiver') OR 
               (sender_id = '$idReceiver' AND receiver_id = '$idSender')";
$result = mysqli_query($con, $check_query);

if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    if($row['status'] == 'accepted') {
        header("Location: Invitation.php?already_friends=1");
        mysqli_close($con);
        exit;
    } else if($row['status'] == 'pending')  {
        header("Location: Invitation.php?already_sent=1");
        mysqli_close($con);
        exit;
    }
   
}

$sql = "INSERT INTO `invitation` (`invitation_id`, `sender_id`, `receiver_id`, `status`) 
        VALUES (NULL, '$idSender', '$idReceiver', 'pending')";

if (mysqli_query($con, $sql)) {
    header("Location: Invitation.php?success=1");
} else {
    header("Location: Invitation.php?error=1");
}

mysqli_close($con);
exit;
?>