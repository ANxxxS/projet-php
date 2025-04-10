<?php
require("DAO.php");

session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $email = $_POST['username'];
    $password_attempt = $_POST['password'];

    $sql = "SELECT * FROM formule WHERE email ='$email';";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password_attempt, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['photo_name'] =$user['photo_name'] ;
            header("Location: user_space.PHP");
            exit();
        } else {
            echo "Mauvais mot de passe";
           
        }
    } else {
      echo "Utilisateur non trouvé";
      
    }
} 
?>