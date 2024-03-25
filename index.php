<?php
require "includes/dbh.inc.php";
require "header.php";
?>

<header class="header">
    <div class="row">
        <div class="col-md-12 text-center">
        <style>
  .logo img {
  width: 250px;
   /* ajustez la taille en fonction de vos préférences */
}
.rating {
            display: inline-block;
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: center;
        }

        .rating > span {
            display: inline-block;
            position: relative;
            width: 1.1em;
            font-size: 20px;
            color: #777777;
            cursor: pointer;
        }

        .rating > span:hover:before,
        .rating > span:hover ~ span:before {
            content: "\2605";
            position: absolute;
            color: #ffcf2e;
        }
        .map {
  position: relative;
  padding-bottom: 56.25%; /* Ratio d'aspect 16:9 pour la hauteur de l'iframe */
  padding-top: 30px; /* Marge supérieure facultative */
  height: 0;
  overflow: hidden;
}

.map iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 70%;
}
.gallery-img {
        width: 100%;
        height: auto;
        object-fit: cover;
        clip-path: ellipse(42% 48% at 50% 50%);
        border: 4px solid #fff;
        transition: transform 0.3s ease-in-out;
    
    }

    .gallery-img:hover {
        transform: scale(1.05);
    }
    .arranging {
  text-align: justify; 
}
.interlined {
  line-height: 1.5;
}
</style>

   <a class="logo"><img src="img/logo_liverpool-1-removebg-preview-ConvertImage" alt="logo"></a>
   </div>
        <div class="col-md-12 text-center">
            <button type="button" onclick="window.location.href='liste_produit.php'" class="btn btn-outline-light btn-lg">
            <em>Commandez dès maintenant et profitez de la livraison !</em></button>
        </div>
    </div>

</header>



<!--about us section-->

<section id="aboutus">

 <div class="container">
   <h2 class="text-center"><br><br>Fast-food Liverpool</h2><br>
   <div class="row">
<!--carousel-->
     <div class="col-sm"><br><br>
      	<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
         <ol class="carousel-indicators">
           <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
           <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
           <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
           <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
           <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
         </ol>
        <div class="carousel-inner">
           <div class="carousel-item active">
             <img class="d-block w-100"  src="img/liverpoll (1)" alt="First slide">
           </div>
          
           <div class="carousel-item">
           <img class="d-block w-100" src="img/liverpoll (3)" alt="Third slide">
           </div>
           <div class="carousel-item">
           <img class="d-block w-100" src="img/liverpoll (6)" alt="Fourth slide">
           </div>
           <div class="carousel-item">
           <img class="d-block w-100" src="img/liverpoll" alt="Fifth slide">
           </div>
        </div>
         <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
           <span class="carousel-control-prev-icon" aria-hidden="true"></span>
           <span class="sr-only">Précédent</span>
         </a>
         <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
           <span class="carousel-control-next-icon" aria-hidden="true"></span>
           <span class="sr-only">Prochain</span>
         </a>
       </div>
     </div>

<!--end of carousel-->

     <div class="col-sm">
    	<div class="arranging">
	<h4 class="text-center">Notre histoire</h4>
	<h6  class="interlined"><br>Le restaurant Liverpool a ouvert ses portes en 2012 grâce à deux frères passionnés de cuisine. Au départ, leur spécialité était un sandwich appelé "Fajitas". Au fil des années, le restaurant a évolué pour offrir une gamme plus large de plats, y compris des pizzas et des sandwiches qui ont rapidement gagné en popularité. Aujourd'hui, Liverpool est connu pour offrir des plats savoureux et abordables, préparés avec des ingrédients frais et de qualité.

Leur menu est varié et comprend une grande sélection de plats pour tous les goûts. Les pizzas sont une spécialité particulièrement populaire, avec une grande variété de garnitures et de sauces pour satisfaire tous les palais.

Avec le succès de leur entreprise, les deux frères ont ouvert plusieurs points de vente à travers la ville, ce qui permet à encore plus de personnes de découvrir les délices culinaires de Liverpool. Leur service rapide et amical, combiné à une excellente nourriture, a contribué à faire de Liverpool un endroit incontournable pour les amateurs de cuisine décontractée.<br><br><br></h6>
	</div>
     </div>
    </div><br>
  
</section>

<!--end of about us section-->
<br>
<br>

<div class="header2">
</div>

<!----gallery -->


<div id="gallery" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Galerie</h2>
        <div class="row">
            <div class="col-6 col-md-3 mb-2">
                <img src="img/azul.jpg" class="img-fluid gallery-img">
                <img src="img/pancakes-with-chocolate-butter-nuts.jpg" class="img-fluid gallery-img">
            </div>
            <div class="col-6 col-md-3 mb-4">
                <img src="img/traditional-mojito-with-ice-mint-table.jpg" class="img-fluid gallery-img">
                <img src="img/side-view-shawarma-pita-roll-with-chicken-fried-potatoes.jpg" class="img-fluid gallery-img">
            </div>
            <div class="col-6 col-md-3 mb-4">
                <img src="img/delicious-hamburger-with-table.jpg" class="img-fluid gallery-img">
                <img src="img/fin-haut-viande-pizza-tomate-poivron-fromage.jpg" class="img-fluid gallery-img">
            </div>
            <div class="col-6 col-md-3 mb-4">
                <img src="img/front-view-orange-juice-with-slice-orange.jpg" class="img-fluid gallery-img">
                <img src="img/steak-served-with-french-fries-salad.jpg" class="img-fluid gallery-img">
            </div>
        </div>
    </div>
</div>
<!----end of gallery -->

<div class="container" id="reservation">
    <div class="container">
        <h1 style="text-align: center;">Exprimez votre satisfaction en notant notre service !</h1><br><br>
        <?php
        // Vérifier si l'utilisateur est connecté

        if (isset($_SESSION['user_id'])) {
            // Récupération de l'ID de l'utilisateur connecté
            $user_id = $_SESSION['user_id'];

            echo '<form action="livre_Or.php" method="POST">';
            echo '<div class="form-group">';
            echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
            echo '<label for="comment_content">Commentaire:</label>';
            echo '<textarea class="form-control" name="comment_content" rows="3" required></textarea>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="rating">Note:</label>';
            echo '<div class="rating">';
            echo '<span onclick="setRating(5)" id="star5">&#9734;</span>';
            echo '<span onclick="setRating(4)" id="star4">&#9734;</span>';
            echo '<span onclick="setRating(3)" id="star3">&#9734;</span>';
            echo '<span onclick="setRating(2)" id="star2">&#9734;</span>';
            echo '<span onclick="setRating(1)" id="star1">&#9734;</span>';
            echo '</div>';
            echo '<input type="hidden" name="rating" id="rating" value="5">';
            echo '</div>';
            echo '<button type="submit" class="btn btn bi bi-arrow-up-right-square " style="background-color: #21BA45; color: white;"> Envoyer le commentaire</button>';
            echo '</form>';
        } else {
            echo '<p>Veuillez vous connecter pour laisser un commentaire.</p>';
            echo '<div class="form-group">';
            echo '<label for="comment_content">Commentaire:</label>';
            echo '<textarea class="form-control" name="comment_content" rows="3" disabled></textarea>';
            echo '</div>';
        }
        ?><br>

        <?php
        try {
            // Récupération des commentaires avec les noms d'utilisateur depuis la table des commentaires et la table des utilisateurs
            $select_comments = $conn->prepare('SELECT comments.*, users.uidUsers AS username FROM comments INNER JOIN users ON comments.user_id = users.user_id ORDER BY comments.comment_id DESC');
            $select_comments->execute();
            $result = $select_comments->get_result();

            $comments = array();
            while ($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }

            if (isset($comments)) {
                $displayed_comments = 1; // Nombre de commentaires affichés initialement
                foreach ($comments as $key => $comment) {
                    if ($key < $displayed_comments) {
                        echo '<div class="card mb-3">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title"> ' . $comment['username'] . '</h5>';
                        echo '<p class="card-text">' . $comment['comment_content'] . '</p>';
                        echo '<p class="card-text">Note: ';
                        for ($i = 1; $i <= $comment['rating']; $i++) {
                            echo '<span style="color: #ffcf2e;">&#9733;</span>';
                        }
                        echo '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                }

                // Vérifier s'il y a plus de commentaires à afficher
                if (count($comments) > $displayed_comments) {
                    echo '<div id="commentsContainer"></div>'; // Conteneur pour les commentaires supplémentaires
                    echo '<button class="btn btn bi bi-eye" id="showMoreComments" style="background-color: #21BA45; color: white;"> Afficher plus de commentaires</button> <br> ';
                    echo '<button class="btn btn d-block mx-auto bi bi-chevron-up" id="showLessComments">
                   Afficher moins
                  </button>
                  ';
                }
            } else {
                echo '<p>Aucun commentaire disponible.</p>';
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des commentaires : " . $e->getMessage();
        }
        ?>
    </div>
</div>

<br>

    <button type="button" onclick="window.location.href='liste_produit.php'" class="btn btn-outline-dark btn-block btn-lg">Commandez maintenant !</button>
        
</div><br><br>

<div  class="container text-center">
<img src="img/Google_Maps_Logo_2020.svg" alt="logo" width="100" height="90"/>
    </div><br>
 
<!-- main page map section-->
<section class="map" id="footer">
    <div class="container text-center">
    
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3196.071656870567!2d3.0214115000000117!3d36.76884840000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128fb2216eadf0d9%3A0xf2d01c04122688ad!2sPizzeria%20Liverpool!5e0!3m2!1sfr!2sdz!4v1683904724074!5m2!1sfr!2sdz" width="1000" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
   
    </div>
</section>

<script>
    var allComments = <?php echo json_encode($comments); ?>;
    var displayedComments = <?php echo $displayed_comments; ?>;

    var showMoreButton = document.getElementById("showMoreComments");
    var showLessButton = document.getElementById("showLessComments");
    var commentsContainer = document.getElementById("commentsContainer");

    showMoreButton.addEventListener("click", function() {
        var remainingComments = allComments.slice(displayedComments); // Commentaires restants à afficher

        // Vérifier s'il y a des commentaires restants
        if (remainingComments.length > 0) {
            var commentsToAdd = remainingComments.slice(0, <?php echo $displayed_comments; ?>);

            // Afficher les commentaires supplémentaires
            commentsToAdd.forEach(function(comment) {
                var card = document.createElement("div");
                card.className = "card mb-3";

                var cardBody = document.createElement("div");
                cardBody.className = "card-body";

                var cardTitle = document.createElement("h5");
                cardTitle.className = "card-title";
                cardTitle.textContent = comment.username;

                var cardText = document.createElement("p");
                cardText.className = "card-text";
                cardText.textContent = comment.comment_content;

                var cardRating = document.createElement("p");
                cardRating.className = "card-text";
                var ratingStars = "";
                for (var j = 1; j <= comment.rating; j++) {
                    ratingStars += '<span style="color: #ffcf2e;">&#9733;</span>';
                }
                cardRating.innerHTML = "Note: " + ratingStars;

                cardBody.appendChild(cardTitle);
                cardBody.appendChild(cardText);
                cardBody.appendChild(cardRating);

                card.appendChild(cardBody);
                commentsContainer.appendChild(card);
            });

            displayedComments += commentsToAdd.length;

            // Vérifier s'il y a encore des commentaires restants
            if (displayedComments >= allComments.length) {
                showMoreButton.style.display = "none";
                showLessButton.style.display = "inline-block";
            }
        }
    });

    showLessButton.addEventListener("click", function() {
        var cards = commentsContainer.getElementsByClassName("card");
        var numCardsToRemove = cards.length - <?php echo $displayed_comments; ?>;

        // Supprimer les commentaires supplémentaires
        for (var i = cards.length - 1; i >= <?php echo $displayed_comments; ?>; i--) {
            commentsContainer.removeChild(cards[i]);
        }

        displayedComments -= numCardsToRemove;

        // Afficher le bouton "Afficher plus de commentaires" et masquer le bouton "Afficher moins de commentaires"
        showMoreButton.style.display = "inline-block";
        showLessButton.style.display = "none";
    });
</script>
<script>
    function setRating(rating) {
        const stars = document.getElementsByClassName("rating")[0].children;

        for (let i = 0; i < stars.length; i++) {
            stars[i].style.color = "#777777";
            stars[i].innerHTML = "&#9734;"; // Remettre toutes les étoiles vides
        }

        for (let i = stars.length - 1; i >= stars.length - rating; i--) {
            stars[i].style.color = "#ffcf2e";
            stars[i].innerHTML = "&#9733;"; // Remplir les étoiles jusqu'à l'étoile sélectionnée
        }

        document.getElementById('rating').value = rating.toString();
    }
</script>

<?php
require "footer.php";
?>