<?php
session_start();

include("DAO.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invitation']) && !empty($_POST['invitation'])) {
    $name = mysqli_real_escape_string($con, $_POST['invitation']);
   $id= $_SESSION['user_id'];
    $sql = "SELECT * FROM `formule` WHERE nom LIKE '$name%' AND `id`<> '$id'";
    $result = mysqli_query($con, $sql);

    $_SESSION['results'] = []; 

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['results'][] = $row;
        }
    }

    header("Location: Invitation.php?search=1");
    exit();
}
?>
