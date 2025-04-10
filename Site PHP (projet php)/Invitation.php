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
            
            </ul>    </div>
  </div>
  <div class="main">
    <div class="topbar">
      <div class="toggle">
        <ion-icon name="menu-outline"></ion-icon>

      </div>
    </div>

    <div class="conntainer">
      <?php
      $results = isset($_SESSION['results']) ? $_SESSION['results'] : [];
      ?>



      <div id="search-tab" class="tab-content active" style="padding-top: 100px;">
        <form id="invi" action="invi.php" method="post">
          <input type="text" name="invitation" placeholder="Rechercher des amis..." required>
          <button type="submit">Chercher</button>
        </form>

        <?php if (isset($_GET['search']) && !empty($results)): ?>
          <div class="invi-container">
            <ul>
              <?php foreach ($results as $user): ?>
                <li>
                  <?php if (!empty($user['photo_name']) && $user['photo_name'] != "noimage.jpg" && !empty($user['id'])): ?>
                    <img src="user_data/<?= htmlspecialchars($user['id']) ?>/<?= htmlspecialchars($user['photo_name']) ?>" alt="Profile">
                  <?php else: ?>
                    <img src="images/noimage.jpg" alt="Profile">
                  <?php endif; ?>
                  <span><?= htmlspecialchars($user['nom']) . " " . htmlspecialchars($user['prenom']) ?></span>

                  <div class="user-actions">
                    <a href="send_invitation.php?user_id=<?= htmlspecialchars($user['id']) ?>" title="Envoyer une invitation">
                      <i class="material-icons action-icon add-invitation">person_add</i>
                    </a>
                    <a href="send_message.php?user_id=<?= htmlspecialchars($user['id']) ?>" title="Envoyer un message">
                      <i class="material-icons action-icon message-user">message</i>
                    </a>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php elseif (isset($_GET['search'])): ?>
          <p>Aucun résultat trouvé.</p>
        <?php endif; ?>
      </div>

      <div id="invitations-tab" class="tab-content">
        <?php
        include("DAO.php");

        $user_id = $_SESSION['user_id'];
        $sql = "SELECT i.invitation_id, i.sender_id, u.nom, u.prenom, u.photo_name 
              FROM invitation i 
              JOIN formule u ON i.sender_id = u.id 
              WHERE i.receiver_id = '$user_id' AND i.status = 'pending' 
              ORDER BY i.invitation_id DESC";

        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
          echo '<div class="invi-container received-invitations">';
          echo '<h3>Invitations reçues</h3>';
          echo '<ul>';

          while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>';

            if (!empty($row['photo_name']) && $row['photo_name'] != "noimage.jpg") {
              echo '<img src="user_data/' . htmlspecialchars($row['sender_id']) . '/' . htmlspecialchars($row['photo_name']) . '" alt="Profile">';
            } else {
              echo '<img src="images/noimage.jpg" alt="Profile">';
            }

            echo '<span>' . htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenom']) . '</span>';

            echo '<div class="invitation-actions">';
            echo '<a href="accept_invitation.php?id=' . htmlspecialchars($row['invitation_id']) . '" title="Accepter l\'invitation">';
            echo '<i class="material-icons action-icon accept-invitation">check_circle</i>';
            echo '</a>';
            echo '<a href="reject_invitation.php?id=' . htmlspecialchars($row['invitation_id']) . '" title="Rejeter l\'invitation">';
            echo '<i class="material-icons action-icon reject-invitation">cancel</i>';
            echo '</a>';
            echo '</div>';

            echo '</li>';
          }

          echo '</ul>';
          echo '</div>';
        } else {
          echo '<p>Vous n\'avez pas d\'invitations en attente.</p>';
        }

        mysqli_close($con);

        ?>
      </div>

      <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
        <script type="text/javascript">
          alert("Invitation envoyée avec succès!");
        </script>
      <?php endif; ?>

      <?php if (isset($_GET['error']) && $_GET['error'] == 1) : ?>
        <script type="text/javascript">
          alert("Erreur lors de l'envoi de l'invitation!");
        </script>
      <?php endif; ?>

      <?php if (isset($_GET['already_sent']) && $_GET['already_sent'] == 1) : ?>
        <script type="text/javascript">
          alert("Une invitation a déjà été envoyée à cet utilisateur!");
        </script>
      <?php endif; ?>

      <?php if (isset($_GET['already_friends']) && $_GET['already_friends'] == 1) : ?>
        <script type="text/javascript">
          alert("Vous êtes déjà amis avec cet utilisateur!");
        </script>
      <?php endif; ?>

      <?php if (isset($_GET['self_invite']) && $_GET['self_invite'] == 1) : ?>
        <script type="text/javascript">
          alert("Vous ne pouvez pas vous envoyer une invitation à vous-même!");
        </script>
      <?php endif; ?>

      <?php if (isset($_GET['accepted']) && $_GET['accepted'] == 1) : ?>
        <script type="text/javascript">
          alert("Invitation acceptée avec succès!");
        </script>
      <?php endif; ?>

      <?php if (isset($_GET['rejected']) && $_GET['rejected'] == 1) : ?>
        <script type="text/javascript">
          alert("Invitation rejetée avec succès.");
        </script>
      <?php endif; ?>
    </div>
  </div>
  <script src="script.js"></script>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>