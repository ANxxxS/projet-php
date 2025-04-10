<?php
require("DAO.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $required = ['nom', 'prenom', 'datenaissance', 'email', 'password1'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires";
            header("Location: inscriptionn.php");
            exit();
        }
    }

 
    if ($_POST['password1'] !== $_POST['password2']) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas";
        header("Location: inscriptionn.php");
        exit();
    }

    $nom = mysqli_real_escape_string($con, $_POST['nom']);
    $prenom = mysqli_real_escape_string($con, $_POST['prenom']);
    $datenaissance = mysqli_real_escape_string($con, $_POST['datenaissance']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $adresse = mysqli_real_escape_string($con, $_POST['adresse']);
    $Sexe = mysqli_real_escape_string($con, $_POST['Sexe']);
    $branche = mysqli_real_escape_string($con, $_POST['branche']);
    $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
    $interet = isset($_POST['interet']) ? implode(';', $_POST['interet']) : '';

   
    $photo_name = 'noimage.jpg';
    $sql = "INSERT INTO formule (nom, prenom, datenaissance, email, adresse, Sexe, branche, interet, password, photo_name) 
            VALUES (
                '$nom',
                '$prenom',
                '$datenaissance',
                '$email',
                '$adresse',
                '$Sexe',
                '$branche',
                '$interet',
                '$password',
                '$photo_name'
            )";

    if (mysqli_query($con, $sql)) {
        $user_id = mysqli_insert_id($con); 
       
        if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png'];
            $file_type = mime_content_type($_FILES['photo']['tmp_name']);
            
            if (in_array($file_type, $allowed_types)) {
                $user_dir = "user_data/$user_id/";
                
                if (!file_exists($user_dir)) {
                    mkdir($user_dir, 0755, true);
                }
                
                $photo_name = uniqid() . '_' . basename($_FILES['photo']['name']);
                $target_path = $user_dir . $photo_name;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
                    $update_sql = "UPDATE formule SET photo_name = '$photo_name' WHERE id = $user_id";
                    mysqli_query($con, $update_sql);
                }
            }
        }
        
        $_SESSION['success'] = "Inscription réussie !";
    } else {
        $_SESSION['error'] = "Erreur base de données : " . mysqli_error($con);
    }

    mysqli_close($con);
    header("Location: index.html");
    exit();
}
?>