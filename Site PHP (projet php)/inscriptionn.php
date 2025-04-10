<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>inscription</title>
   
</head>
<body>
<div class="navigation">
<ul>
    <li>
        <img src="./CSS/btskenitra.png" alt="LOGO">
    </li>
   

    <li>
        <a href="inscriptionn.php">
            <span class="icon">
                <i class="material-icons">how_to_reg</i>
            </span>
            <span class="title">inscription</span>
        </a>
    </li>

    <li>
        <a href="index.html">
            <span class="icon">
                <i class="material-icons">login</i>
            </span>
            <span class="title">login</span>
        </a>
    </li>

</ul>
        </div>
        <div class="main">
        <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                  
                </div>
            </div>
    <div id="Inscriptions" style=" width: 100%;
    height: 80vh; 
    padding-top: 5vh;
    box-sizing: border-box;
    ">  
    
        <div class="signup-container">
            <h2>Inscription</h2>
            <form class="Inscriptions" action="inscription.php" method="post" enctype="multipart/form-data">
                  
                  
                  <input type="text" name="nom" id="namId" placeholder="nom">
                  
                
               
                  <input type="text" name="prenom" id="prenomId" placeholder="prenom">
              
                  <label for="datenaissanceId">date Naissance</label>
                  
                  <input type="date" name="datenaissance" id="datenaissanceId">
                  
               
                  <input type="email" name="email" id="emailId" placeholder="email@gmail.com" >
             
                 
                  <textarea name="adresse" id="adreeseId" placeholder="Adresse"></textarea>
                <div>
                 
                  <label for="SexeHomme">H<input type="radio" name="Sexe" value="Homme" id="SexeHomme"></label>
                  <label for="SexeFemme">F<input type="radio" name="Sexe" value="Femme" id="SexeFemme"></label>
                </div>
                  <select name="branche" id="">                      
                    <option value="" selected hidden>branche</option>
                      <option value="dsi"> Développement des Systèmes dInformation</option>
                      <option value="se">Systèmes Electroniques</option>
                      <option value="cpi"> Conception du Produit Industriel</option>
                      <option value="elt">Electrothecniques</option>
                      <option value="mi">Maintenance Industrielle</option>
                  </select>
                
                  <div>
                    <div>
                  <label for="voyage">professeur</label>        
                  <input type="checkbox" id="voyage" name="interet[]" value="professeur">
                </div><div>
                  <label for="sport">etudiante</label>
                  <input type="checkbox" id="sport" name="interet[]" value="etudiante">
                </div>
                 <label for="photoId">photo</label>
                  <input type="file" name="photo" id="photoId">
                </div>
                <input type="password" name="password1" placeholder="Mot de passe">
                <input type="password" name="password2" placeholder="Confirmez le mot de passe">
              
                  <button type="submit">s'inscrire</button>
            </form>
        </div>
    </div>    </div>
    <script src="script.js"></script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>
</html>