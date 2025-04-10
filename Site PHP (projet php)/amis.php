<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <title>Document</title>
</head>
<body>
 
    <div class="container">
        <div class="navigation">
        <ul>
                <li>
                <img src="./<?php 
                session_start();
                if(!isset($_SESSION['email'])){
                    header("Location: index.html");
                    exit;
                }
    if (!empty($_SESSION['photo_name']) && $_SESSION['photo_name'] != "noimage.jpg" && !empty($_SESSION['user_id'])) {
        echo "user_data/" . htmlspecialchars($_SESSION['user_id']) . "/" . htmlspecialchars($_SESSION['photo_name']);
    } else {
        echo "images/noimage.jpg";
    }
?>" 
alt="Profile" 
style="width: 200px; 
       height: 200px; 
       border-radius: 50%; 
       object-fit: cover;">
       <span style="margin: 80px;"><?php echo $_SESSION['nom'].' '.$_SESSION['prenom'] ?></span>
                </li>
                <li>
                    <a href="user_space.PHP">
                        <span class="icon">
                            <i class="material-icons">home</i>
                        </span>
                        <span class="title">Home</span>
                    </a>
                </li>
              
               
                <li>
                    <a href="Invitation.php">
                        <span class="icon">
                            <i class="material-icons">send</i>
                        </span>
                        <span class="title">Invitation</span>
                    </a>
                </li>
                <li>
                    <a href="amis.php">
                        <span class="icon">
                            <i class="material-icons">group</i>
                        </span>
                        <span class="title">amis</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <span class="icon">
                            <i class="material-icons">logout</i>
                        </span>
                        <span class="title">log out</span>
                    </a>
                </li>
            
            </ul>
        </div>
</div>
<div class="main">
    <div class="topbar">
        <div class="toggle">
            <ion-icon name="menu-outline"></ion-icon>
         
        </div>
    </div>
    <div class="conntainer">
    <div class="invi-container">
        <?php
        include("DAO.php");
        $user_id = $_SESSION['user_id'];
        
        $sql = "SELECT i.invitation_id, i.sender_id, i.receiver_id, u.nom, u.prenom, u.photo_name
                FROM invitation i
                JOIN formule u ON i.sender_id = u.id
                WHERE (i.receiver_id = '$user_id' OR i.sender_id = '$user_id') 
                AND i.status = 'accepted'
                ORDER BY i.invitation_id DESC";
        
        $result = mysqli_query($con, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="invi-container received-invitations style="    margin-top: 10px;"">';
            echo '<h3>La Liste des Amis </h3>';
            echo '<ul>';
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li>';
                $id25 = $row['receiver_id'];
                
                if ($row['sender_id'] != $user_id) {
                    if (!empty($row['photo_name']) && $row['photo_name'] != "noimage.jpg") {
                        echo '<img src="user_data/' . htmlspecialchars($row['sender_id']) . '/' . htmlspecialchars($row['photo_name']) . '" alt="Profile">';
                    } else {
                        echo '<img src="images/noimage.jpg" alt="Profile">';
                    }
                    
                    echo '<span>' . htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenom']) . '</span>';
                    echo '</li>';
                } else {
                    $sql2 = "SELECT id, nom, prenom, photo_name
                            FROM formule
                            WHERE id = '$id25'";
                    
                    $result2 = mysqli_query($con, $sql2);
                    
                    if ($DATA = mysqli_fetch_assoc($result2)) {
                        if (!empty($DATA['photo_name']) && $DATA['photo_name'] != "noimage.jpg") {
                            echo '<img src="user_data/' . htmlspecialchars($DATA['id']) . '/' . htmlspecialchars($DATA['photo_name']) . '" alt="Profile">';
                        } else {
                            echo '<img src="images/noimage.jpg" alt="Profile">';
                        }
                        
                        echo '<span>' . htmlspecialchars($DATA['nom']) . ' ' . htmlspecialchars($DATA['prenom']) . '</span>';
                    }
                    echo '</li>';
                }
            }
            
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<p>Vous n\'avez pas des amis.</p>';
        }
        
        mysqli_close($con);
        ?>
    </div>
</div>
</div>
<script src="script.js"></script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>