<?php require "header.php"; ?>

<br>
<style>
  .list-group-item {
    border: none;
    padding: 10px 15px;
    transition: all 0.2s ease-in-out;
  }

  .list-group-item:hover {
    background-color: #f2f2f2;
  }

  .list-group-item.active {
    background-color: #007bff;
    color: #fff;
  }

  .list-group-item.active:hover {
    background-color: #0069d9;
  }

  .list-group-item a {
    color: inherit;
  }

  .list-group-item a:hover {
    text-decoration: none;
  }
  
  .list-group-item .fa {
    float: right;
    margin-top: 4px;
  }
  .list-group-item.active .fa {
    color: #fff;
  }
</style>

<body>

<div class="container my-5">
    
<?php

require "includes/dbh.inc.php";

    // Récupération des catégories de produits
    $sql = "SELECT * FROM categories ORDER BY nom_categorie";
    $result = $conn->query($sql);

    // Vérification si la requête a réussi
    if (!$result) {
        die("Erreur: " . $conn->error);
    }
    
    // Tableau pour stocker les catégories
    $categories = array();

    // Traitement des résultats de la requête
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $idCategorie = $row["id_categorie"];
            $nomCategorie = $row["nom_categorie"];

            // Ajout de la catégorie au tableau
            $categories[$idCategorie] = $nomCategorie;
        }
    }
    ?>

    <div class="row">
  
        <div class="col-md-3">
           <ul class="list-group bg-white text-black rounded p-2">
               <li class="list-group-item <?php if(!isset($_GET["categorie"]) || $_GET["categorie"]=="null") ;?>">
                   <a href="?categorie=null" class="text-black font-weight-bold text-decoration-none">Tous les produits</a>
               </li>
                     <?php foreach ($categories as $idCategorie => $nomCategorie) { ?>
                       <li class="list-group-item <?php if(isset($_GET["categorie"]) && $_GET["categorie"]==$idCategorie) ;?>">
                           <a href="?categorie=<?= $idCategorie ?>" class="text-black fs-5 text-decoration-none"><?= $nomCategorie ?></a>
                       </li>
                     <?php } ?>
              </ul>
            </div>

            <div class="col-md-9">
            <div id="produits-container" class="row">
                <?php
                // Récupération de tous les produits avec leurs tailles, prix et quantités
                $sql = "SELECT produits.id_produit, produits.nom_produit, produits.description, produits.image, 
                produits.id_categorie, tailles.id_taille, tailles.nom_taille, produits_tailles.prix_taille, produits_tailles.quantite
                FROM produits
                INNER JOIN produits_tailles ON produits.id_produit = produits_tailles.id_produit
                INNER JOIN tailles ON produits_tailles.id_taille = tailles.id_taille
                WHERE produits.statut = 'actif'";
        
        // Filtrage des produits par catégorie si nécessaire
        if (isset($_GET["categorie"]) && $_GET["categorie"] !== "null") {
            $idCategorie = $_GET["categorie"];
            $sql .= " AND produits.id_categorie = $idCategorie";
        }
        
        $sql .= " ORDER BY produits.nom_produit";
        $result = $conn->query($sql);

                // Vérification si la requête a réussi
                if (!$result) {
                    die("Erreur: " . $conn->error);
                }

                // Tableau pour stocker les produits et leurs tailles, prix, quant
                $produits = array();   

                  // Traitement des résultats de la requête
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $idProduit = $row["id_produit"];
                        $nomProduit = $row["nom_produit"];
                        $descriptionProduit = $row["description"];
                        $imageProduit = $row["image"];
                        $idCategorieProduit = $row["id_categorie"];
                        $idTaille = $row["id_taille"];
                        $nomTaille = $row["nom_taille"];
                        $prixTaille = $row["prix_taille"];
                        $quantite = $row["quantite"];
    
                        // Ajout du produit et de sa taille, prix, quantité au tableau
                        if (!isset($produits[$idProduit])) {
                            $produits[$idProduit] = array(
                                "id_produit" => $idProduit,
                                "nom_produit" => $nomProduit,
                                "description" => $descriptionProduit,
                                "image" => $imageProduit,
                                "id_categorie" => $idCategorieProduit,
                                "tailles" => array()
                            );
                        }
                        $produits[$idProduit]["tailles"][$idTaille] = array(
                            "id_taille" => $idTaille,
                            "nom_taille" => $nomTaille,
                            "prix_taille" => $prixTaille,
                            "quantite" => $quantite
                        );
                    }
                }
             ?>
                <div class="row">
                <?php foreach ($produits as $produit) { ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <img src="<?= "img/" . $produit["image"]  ?>" class="card-img-top" alt="<?= $produit["nom_produit"] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $produit["nom_produit"] ?></h5>
                                <p class="card-text"><?= $produit["description"] ?></p>
                            </div>
                            <form action="ajouter_panier.php" method="post">
                                <input type="hidden" name="id_produit" value="<?= $produit["id_produit"] ?>">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="form-group">
                                            <label for="taille">Taille :</label>
                                            <select class="form-control" id="taille" name="taille">
                                                <?php foreach ($produit["tailles"] as $taille) { ?>
                                                    <option value="<?= $taille["id_taille"] ?>"><?= $taille["nom_taille"] ?> - <?= $taille["prix_taille"] ?> da</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-group">
                                            <label for="quantite">Quantité :</label>
                                            <input type="number" class="form-control" id="quantite" name="quantite" min="1" max="10" value="1">
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                    <a href="delete_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    <a href="update_produit.php?id_produit=<?= $produit['id_produit'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </div> 