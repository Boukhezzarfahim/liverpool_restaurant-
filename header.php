<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="icon" type="image/png" sizes="40x40" href="img/logo_liverpool-1-removebg-preview.png">   
<title>Liverpool</title>
<meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/c.css" rel="stylesheet" type="text/css">     <!--style.css document-->
  <link href="css/font-awesome.min.css" rel="stylesheet">     <!--font-awesome-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">  <!--bootstrap-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  <!--googleapis jquery-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">  <!--font-awesome-->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>                          <!--bootstrap-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>           <!--bootstrap-->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<!--bootstrap-->

</head>
<style>
.flex-column { 
       max-width : 260px;
   }
           
.container {
            background: #fff;
            
        }
@media (min-width: 1130px) {
.container{
        max-width: 1330px;
    }
}
      
.img {
            margin: 5px;
        }

.logo img{
	 width:150px;
	 height:250px;
	margin-top:90px;
	margin-bottom:40px;
}
.navbar {
  background-color: black;
}

.navbar-nav {
  margin-right: auto;
}

.nav-item {
  margin-left: 10px;
}

.nav-link {
  color: #fff !important;
  font-size: 17px;
  transition: all 0.2s ease-in-out;
}

.nav-link:hover {
  color: #ffc107 !important;
}

.fa-shopping-cart {
  font-size: 24px;
  margin-right: 10px;
}

.btn-outline-white {
  border-color: #fff;

}

.btn-outline-white:hover {
  background-color: #fff;
  color: #333;
}
.cart-count {
    display: inline-block;
    background-color: red;
    color: white;
    font-size: 12px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 50%;
    margin-left: 5px;
}

</style>

<body>
<nav class="navbar navbar-expand-lg navbar-light fixed-top opacity-75">
    <div class="container bg-black">
        <a class="navbar-nav text-white text-decoration-none" href="index.php">
            <h2><strong><em>Liverpool</em></strong></h2>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navi">
            <span class="navbar-toggler-icon bg-white"></span>
        </button>
        <div class="collapse navbar-collapse" id="navi">
            <ul class="navbar-nav mr-auto">
                <?php
              
                if(isset($_SESSION['user_id'])) {
                    echo '
                    
                    ';

                    if($_SESSION['role'] == 2) {
                        echo '
                            <li class="nav-item">
                                <a class="nav-link text-white bi bi-plus-square" href="traitement_ajout_produit.php"> Produits </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white bi bi-command" href="admin_commande.php">  Commandes </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bi bi-pencil-square text-white" href="gestion_produit.php"> Modifier un produit</a>
                            </li>
                            <li class="nav-item">
                               <a class="nav-link text-white" href="gestion_users.php">
                                   <i class="bi bi-people-fill"></i> Utilisateurs
                               </a>
                           </li>
                             <li class="nav-item">
                             <a class="nav-link text-white" href="gestion_commentaires.php">
                                  <i class="bi bi-chat"></i> Commentaires
                             </a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link bi bi-bar-chart-fill text-white" href=" stats_commande.php"> Statistiques</a>
                        </li>
                           
                        ';
                    } else {
                        echo '
                            <li class="nav-item">
                                <a class="nav-link bi bi-info-circle-fill text-white" href="#aboutus"> À propos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bi bi-images text-white" href="#gallery"> Galerie</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bi bi-geo-fill text-white" href="#footer"> Adresse</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white bi bi-cart" href="liste_produit.php"> Menu </a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link text-white bi bi-clock-history" href="soft_client_commande.php"> Historique de commandes</a>
                        </li>
                            
                        ';
                    }
                } else {
                    
                    echo '
                        <li class="nav-item">
                            <a class="nav-link bi bi-info-circle-fill text-white" href="#aboutus"> À propos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bi bi-images text-white" href="#gallery"> Galerie</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link bi bi-geo-fill text-white" href="#footer"> Adresse</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white bi bi-cart" href="liste_produit.php"> Menu </a>
                        </li>
                    ';
                }
                ?>
            </ul>
            <?php
            // Logout button when user is logged in
            if(isset($_SESSION['user_id'])) {
                if ($_SESSION['role'] != 2) {
                    echo '
                        <div>
                            <ul class="navbar-nav ml-auto">
                                <li>
                                    <a class="nav-link bi bi-cart text-white" href="panier.php">
                                        Panier<span class="cart-count cart-count">' . (isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0) . '</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link bi bi-box-arrow-right text-white" href="includes/logout.inc.php">
                                        Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </div>
                    ';
                } else {
                    echo '
                        <div>
                            <ul class="navbar-nav ml-auto">
                                <li>
                                    <a class="nav-link bi bi-box-arrow-right text-white" href="includes/logout.inc.php">
                                        Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </div>
                    ';
                }
            } else {
                echo '
                    <div >
                        <ul class="navbar-nav ml-auto">
                            <li>
                            <a class="nav-link text-white" data-toggle="modal" data-target="#myModal_reg">
                            <i class="bi bi-person-plus-fill"></i> S\'inscrire
                        </a>
                         </li>
                            <li>
                            <a class="nav-link text-white" data-toggle="modal" data-target="#myModal_login">
                            <i class="bi-door-open"></i> Se connecter
                        </a>
                        </li>
                        </ul>
                    </div>
                ';
            }
            ?>
        </div>
    </div>
</nav>

<div class="container">
                          
    <div class="modal fade" id="myModal_login">
        <div class="modal-dialog">
          <div class="modal-content">

          
            <div class="modal-header">
              <h4 class="modal-title">Se connecter</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

      
            <div class="modal-body">
            
            <?php
            if(isset($_GET['error1'])){
        
            //script  error 
            echo '  <script>
                    $(document).ready(function(){
                    $("#myModal_login").modal("show");
                    });
                    </script> ';
        
        
            //error  log in
        
            if($_GET['error1'] == "emptyfields") {   
            echo '<h5 class="text-danger text-center">Essaye encore!</h5>';
            }
            else if($_GET['error1'] == "error") {   
            echo '<h5 class="text-danger text-center">Veuillez vous connecter si vous avez un compte.</h5>';
            }
            else if($_GET['error1'] == "wrongpwd") {   
                echo '<h5 class="text-danger text-center">Le mot de passe est incorrect, veuillez réessayer.</h5>';
            }
            else if($_GET['error1'] == "error2") {   
                echo '<h5 class="text-danger text-center">Essayez encore!</h5>';
            }
            else if($_GET['error1'] == "nouser") {   
                echo '<h5 class="text-danger text-center">Le nom d\'utilisateur ou l\'adresse e-mail est incorrect, veuillez réessayer.</h5>';
                }
            }
            echo'<br>';
            ?>  
            
                    <div class="signin-form">
                    <form action="includes/login.inc.php" method="post">
                        <p class="hint-text">Si vous avez un compte, veuillez vous connecter.</p>
                    <div class="form-group">
                        <input type="text" class="form-control" name="mailuid" placeholder="Nom d'utilisateur au bien e-mail" required="required">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="pwd" placeholder="Mot de passe" required="required">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="login-submit" class="btn btn-dark btn-lg btn-block">Se connecter</button>
                    </div>
                            </form>
                    </div>   
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Fermez</button>
                </div>
            </div>
        </div>
    </div> 
</div>

    
<div class="container">
  <!-- The Modal -->
    <div class="modal fade" id="myModal_reg">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">S'inscrire</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>      
            <!-- Modal body -->
                <div class="modal-body">   

                <?php
                if(isset($_GET['error'])){
                    //script for modal to appear when error 
                    echo '  <script>
                                $(document).ready(function(){
                                $("#myModal_reg").modal("show");
                                });
                            </script> ';


                    //error handling for errors and success --sign up form

                    if($_GET['error'] == "emptyfields") {   
                        echo '<h5 class="text-danger text-center">Essayez encore une fois!</h5>';
                    }
                    else if($_GET['error'] == "invalidemailusername") {   
                        echo '<h5 class="text-danger text-center">Nom utilisateur ou l\'e-mail existe déja.</h5>';
                    }
                    else if($_GET['error'] == "invalidemail") {   
                        echo '<h5 class="text-danger text-center">E-mail invalide, veuillez réessayer.</h5>';
                    }
                    else if($_GET['error'] == "usernameemailtaken") {   
                        echo '<h5 class=" text-danger text-center">Nom utilisateur ou l\'e-mail existe déja, veuillez réessayer.</h5>';
                    }
                    else if($_GET['error'] == "invalidusername") {   
                        echo '<h5 class=" text-danger text-center">Nom d\'utilisateur n\'est pas valide , veuillez réessayer.</h5>';
                    }
                    else if($_GET['error'] == "invalidpassword") {   
                        echo '<h5 class=" text-danger text-center">Mot de passe invalide n\'est pas identique, veuillez réessayer.</h5>';
                    }
                    else if($_GET['error'] == "passworddontmatch") {   
                        echo '<h5 class=" text-danger text-center">Mot de passe doit etre identique, veuillez réessayer.</h5>';
                    }
                    else if($_GET['error'] == "error1") {   
                        echo '<h5 class=" text-danger text-center">Erreur, veuillez réessayer.</h5>';
                    }
                    else if($_GET['error'] == "error2") {   
                        echo '<h5 class=" text-danger text-center">Erreur, veuillez réessayer.</h5>';
                    }
                }
                if(isset($_GET['signup'])) { 
                        //script for modal to appear when success
                    echo '  <script>
                                $(document).ready(function(){
                                $("#myModal_reg").modal("show");
                                });
                            </script> ';

                    if($_GET['signup'] == "success"){ 
                        echo '<h5 class="text-success text-center">
                        Félicitations pour votre inscription réussie ! Veuillez maintenant vous connecter.</h5>';
                    }
                }
                echo'<br>';
                ?>

                    <div class="signup-form">
                        <form action="includes/signup.inc.php" method="post">
                            <p class="hint-text">Créez votre compte</p>
                            <div class="form-group">
                                    <input type="text" class="form-control" name="uid" placeholder="Nom et prénom" required="required">
                                    
                            </div>
                            <div class="form-group">
                                    <input type="email" class="form-control" name="mail" placeholder="E-mail" required="required">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="pwd" placeholder="Mot de passe" required="required">
                                <small class="form-text text-muted">Mot de passe 6-20 characters de longeur</small>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="pwd-repeat" placeholder="Confirmez le mot de passe" required="required">
                            </div>        
                            <div class="form-group">
                                <label class="checkbox-inline"><input type="checkbox" required="required">J'accepte <a href="#">termes d'utilisation</a> 
                                &amp; <a href="#">Privacy Policy</a></label>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="signup-submit" class="btn btn-dark btn-lg btn-block">S'inscrire maintenant</button>
                            </div>
                        </form>
                            <div class="text-center">Avez-vous déja un compte? <a href="#">S'inscrire</a></div>
                    </div> 	
                </div>        
                <!-- Modal footer -->
                <div class="modal-footer">

                      <button type="button" class="btn btn-danger" data-dismiss="modal">Fermez</button>
                </div> 
            </div>
        </div>
    </div>
</div>