<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="./CSS/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

    <title>Document</title>
    <style>
        .post-form {
           
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .post-form h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .post-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }

        .post-form input[type="file"] {
            margin-bottom: 15px;
        }

        .post-form button {
            padding: 10px 15px;
            background:rgb(134, 125, 173);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .post-form button:hover {
            background:rgb(69, 78, 160);
        }

        .posts-container {
            max-width: 800px;
            margin: 20px auto;
        }

        .post {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
        }
        
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .post-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .post-content img {
            max-width: 100%;
            border-radius: 4px;
            margin-top: 10px;
        }

        .post-footer {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }

        .post-actions button {
            background: none;
            border: none;
            cursor: pointer;
            color: #555;
            margin-right: 15px;
        }

        .post-actions button:hover {
            color: #000;
        }
       
        .post-date {
            color: #777;
            font-size: 0.9em;
        }
        
    </style>
</head>

<body>

    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <img src="./<?php
                                session_start();
                                if (!isset($_SESSION['email'])) {
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
                    <span style="margin: 80px;"><?php echo $_SESSION['nom'] . ' ' . $_SESSION['prenom'] ?></span>
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
                <ion-icon name="menu-outline" style="color: black;"></ion-icon>

            </div>
        </div>
        <div class="post-form" style="    margin-top: 80px;">
            <h2>Créer un post</h2>
            <form id="postForm" enctype="multipart/form-data">
                <textarea name="caption" placeholder="Écrivez quelque chose..." rows="3"></textarea>
                <input type="file" name="image" accept="image/*" required>
                <button type="submit">Publier</button>
            </form>
        </div>

        <div class="posts-container"  id="postsContainer">
        </div>
    </div>
    <!--  -->

    </div>
   


<div class="boite_des_amis" id="boite_user">
  <h1>Boite des amis</h1>
  
</div>
    <div class="boite_des_message" style="right: 220px;"id="conteneur_boites_messages_principal">
    </div>
    <div id="boiteMessage"style="right: 220px;" ></div>



    <script src="script.js"></script>
    <script src="user_cont.js"></script>
    <script src="posts.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>