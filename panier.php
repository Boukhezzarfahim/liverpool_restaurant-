<?php
session_start();

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}

require "includes/dbh.inc.php";

if (isset($_POST['ajout_panier'])) {
    $id_produit = $_POST['id_produit'];
    $taille_id = $_POST['taille'];
    $quantite = $_POST['quantite'];

    $sql = "SELECT produits.id_produit, produits.nom_produit, produits.description, produits.image, produits.id_categorie,
             tailles.id_taille, tailles.nom_taille, produits_tailles.prix_taille, produits_tailles.quantite
             FROM produits
             INNER JOIN produits_tailles ON produits.id_produit = produits_tailles.id_produit
             INNER JOIN tailles ON produits_tailles.id_taille = tailles.id_taille
             WHERE produits.id_produit = ? AND tailles.id_taille = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_produit, $taille_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $nom_taille = $row["nom_taille"];
        $prix_taille = $row["prix_taille"];
        $prix_total = $prix_taille * $quantite;

        $image_produit = "img/" . $row["image"];

        $article = array(
            "id_produit" => $id_produit,
            "nom_produit" => $row["nom_produit"],
            "image" => $image_produit,
            "taille" => $nom_taille,
            "quantite" => $quantite,
            "prix_unitaire" => $prix_taille,
            "prix_total" => $prix_total
        );

        array_push($_SESSION['panier'], $article);

        header("Location: liste_produit.php");
        exit;
    }
}

if (isset($_SESSION['user_id'])) {
    $id_user = $_SESSION['user_id'];

    if (isset($_POST['supprimer_article'])) {
        $id_produit = $_POST['id_produit'];

        foreach ($_SESSION['panier'] as $key => $article) {
            if ($article['id_produit'] == $id_produit) {
                unset($_SESSION['panier'][$key]);
                break;
            }
        }
    }

    if (isset($_POST['supprimer_tous_articles'])) {
        $_SESSION['panier'] = array();

        header("Location: panier.php");
        exit;
    }

    if (isset($_POST['Valider'])) {
        $adresse = $_POST['adresse'];
        $nom = $_POST['nom'];
        $telephone = $_POST['telephone'];
        $commentaires = $_POST['commentaires'];
        $statut = 'en attente';
        $etat = 'actif';

        $sql_commande = "INSERT INTO commande (id_user, adresse, nom, telephone, date, commentaire, statut, etat) 
                         VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
        $stmt_commande = $conn->prepare($sql_commande);
        $stmt_commande->bind_param("issssss", $id_user, $adresse, $nom, $telephone, $commentaires, $statut, $etat);
        $stmt_commande->execute();

        $id_commande = $stmt_commande->insert_id;
        $stmt_commande->close();

        foreach ($_SESSION['panier'] as $article) {
            $id_produit = $article['id_produit'];
            $quantite = $article['quantite'];
            $prix_unitaire = $article['prix_unitaire'];
            $taille = $article['taille'];

            $sql_details = "INSERT INTO produit_commande (id_commande, id_produit, quantite, prix_unitaire, taille) 
                            VALUES (?, ?, ?, ?, ?)";
            $stmt_details = $conn->prepare($sql_details);
            $stmt_details->bind_param("iiids", $id_commande, $id_produit, $quantite, $prix_unitaire, $taille);
            $stmt_details->execute();
            $stmt_details->close();
        }

        $_SESSION['panier'] = array();
        $_SESSION['commande_validee'] = true;

        echo '<script>alert("Votre commande a bien été passée on vous appelle dans quelques instants !"); window.location.href = "liste_produit.php?id='.$id_commande.'";</script>';
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}

require "header.php";
?>

<br>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Bouton de retour -->
            <table class="table table-striped">
                <tbody>
                    <?php foreach ($_SESSION['panier'] as $article) { ?>
                        <tr>
                            <td>
                                <div class="rounded-circle overflow-hidden" style="width: 100px; height: 100px;">
                                    <img src="<?= $article['image'] ?>" alt="Image produit" class="img-fluid" style="width: 100%;">
                                </div>
                            </td>
                            <td><b><?= $article['nom_produit'] ?></b></td>
                            <td><b><?= $article['taille'] ?></b></td>
                            <td><b><?= $article['quantite'] ?></b></td>
                            <td><b><?= $article['prix_unitaire'] ?> da</b></td>
                            <td><b><?= $article['prix_total'] ?> da</b></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="id_produit" value="<?= $article["id_produit"] ?>">
                                    <button type="submit" name="supprimer_article" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5"><b>Total :</b></td>
                        <td colspan="2"><?= array_sum(array_column($_SESSION['panier'], 'prix_total')) ?> da</td>
                    </tr>
                </tfoot>
            </table>
            <?php if (!empty($_SESSION['panier'])) { ?>
                <form action="" method="post">
                    <button type="submit" name="supprimer_tous_articles" class="btn btn-danger"><b>Vider le panier</b></button>
                </form>
            <?php } ?>
        </div>
        <div class="col-lg-4">
            <?php if (!empty($_SESSION['panier'])) { ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="adresse">Adresse de livraison :</label>
                        <textarea class="form-control" name="adresse" id="adresse" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom et prénom :</label>
                        <input type="text" class="form-control" name="nom" id="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Numéro de téléphone :</label>
                        <input type="text" class="form-control" name="telephone" id="telephone" required>
                    </div>
                    <div class="form-group">
                        <label for="commentaires">Commentaires :</label>
                        <textarea class="form-control" name="commentaires" id="commentaires"></textarea>
                    </div>
                    <button type="submit" name="Valider" class="btn btn-success"><b>Valider la commande</b></button>
                </form>
            <?php } else { ?>
                <p>Votre panier est vide.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>
