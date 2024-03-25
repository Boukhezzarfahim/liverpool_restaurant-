<?php
// Connexion à la base de données
$connexion = new PDO('mysql:host=localhost;dbname=liverpool;charset=utf8', 'root', '');

// Récupération de l'ID du produit à mettre à jour
$id_produit = $_GET['id_produit'];

// Récupération des données du produit à mettre à jour
$select_produit = $connexion->prepare('SELECT * FROM produits WHERE id_produit = ?');
$select_produit->execute(array($id_produit));
$produit = $select_produit->fetch(PDO::FETCH_ASSOC);

// Récupération des catégories disponibles depuis la table des catégories
$select_categories = $connexion->query('SELECT * FROM categories');
$categories = $select_categories->fetchAll(PDO::FETCH_ASSOC);

// Récupération des tailles disponibles depuis la table des tailles
$select_tailles = $connexion->query('SELECT * FROM tailles');
$tailles = $select_tailles->fetchAll(PDO::FETCH_ASSOC);

// Récupération des tailles associées au produit depuis la table produits_tailles
$select_produit_tailles = $connexion->prepare('SELECT * FROM produits_tailles WHERE id_produit = ?');
$select_produit_tailles->execute(array($id_produit));
$produit_tailles = $select_produit_tailles->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du produit
    $nom_produit = $_POST['nom_produit'];
    $description = $_POST['description'];
    $image_nom = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $categorie = $_POST['categorie'];
    $disponibilite = isset($_POST['disponibilite']) ? 1 : 0;
    $non_disponible = isset($_POST['non_disponible']) ? 1 : 0;

    // Vérification si la catégorie existe déjà dans la table des catégories
    $select_categorie = $connexion->prepare('SELECT id_categorie FROM categories WHERE nom_categorie = ?');
    $select_categorie->execute(array($categorie));
    $id_categorie = $select_categorie->fetchColumn();

    // Si la catégorie n'existe pas, l'insérer dans la table des catégories
    if (!$id_categorie) {
        $insert_categorie = $connexion->prepare('INSERT INTO categories (nom_categorie) VALUES (?)');
        $insert_categorie->execute(array($categorie));
        $id_categorie = $connexion->lastInsertId();
    }

    // Vérification si un fichier d'image a été téléchargé
    if (!empty($image_nom)) {
        // Mise à jour du produit avec la nouvelle image
        $update_produit = $connexion->prepare('UPDATE produits SET nom_produit = ?, description = ?, image = ?, id_categorie = ?, disponibilite = ? WHERE id_produit = ?');
        $update_produit->execute(array($nom_produit, $description, $image_nom, $id_categorie, $disponibilite, $id_produit));

        // Déplacement de la nouvelle image téléchargée dans le répertoire souhaité
        move_uploaded_file($image_tmp, 'img/' . $image_nom);
    } else {
        // Récupération de l'URL de l'image existante
        $select_image = $connexion->prepare('SELECT image FROM produits WHERE id_produit = ?');
        $select_image->execute(array($id_produit));
        $image_existante = $select_image->fetchColumn();

        // Mise à jour du produit sans changer l'image
        $update_produit = $connexion->prepare('UPDATE produits SET nom_produit = ?, description = ?, id_categorie = ?, disponibilite = ? WHERE id_produit = ?');
        $update_produit->execute(array($nom_produit, $description, $id_categorie, $disponibilite, $id_produit));
    }

// Suppression des tailles existantes du produit dans la table produits_tailles
$delete_produit_tailles = $connexion->prepare('DELETE FROM produits_tailles WHERE id_produit = ?');
$delete_produit_tailles->execute(array($id_produit));

// Traitement des tailles
$tailles = $_POST['taille'];
$prix_tailles = $_POST['prix_taille'];
$quantites = $_POST['quantite'];

// Vérifier si des tailles ont été cochées
if (!empty($tailles)) {
    // Construire la requête d'insertion
    $sql = 'INSERT INTO produits_tailles (id_produit, id_taille, prix_taille, quantite) VALUES ';
    $params = array();

    // Créer des placeholders pour chaque enregistrement
    $placeholders = array_fill(0, count($tailles), '(?, ?, ?, ?)');

    // Fusionner les placeholders avec des virgules pour la requête
    $sql .= implode(', ', $placeholders);

    foreach ($tailles as $key => $id_taille) {
        $params[] = $id_produit;
        $params[] = $id_taille;
        $params[] = $prix_tailles[$key];
        $params[] = $quantites[$key];
    }

    // Préparer et exécuter la requête d'insertion
    $insert_produit_taille = $connexion->prepare($sql);
    $insert_produit_taille->execute($params);
}

// Redirection vers une page de confirmation
header('Location: gestion_produit.php');
exit;

}



?>
<?php require "header.php"; ?><br><br><br>
<!-- Le reste du code HTML -->

<div class="container">
    <h2 style="text-align: center; background-color: #00B2A9; color: white; border-radius: 15px; padding: 10px;">Modifier un produit</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nom_produit">Nom du produit:</label>
            <input type="text" class="form-control" id="nom_produit" name="nom_produit" value="<?php echo $produit['nom_produit']; ?>">
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description"><?php echo $produit['description']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <?php if (!empty($produit['image'])) : ?>
                <img src="img/<?php echo $produit['image']; ?>" alt="Image actuelle" width="100">
                <div class="form-check mt-2">
                </div>
            <?php endif; ?>
            <input type="file" class="form-control-file" id="image" name="image">
        </div><br>
        <div class="form-group">
            <label for="categorie">Catégorie:</label>
            <select class="form-control" id="categorie" name="categorie">
                <?php foreach ($categories as $categorie) : ?>
                    <option value="<?php echo $categorie['nom_categorie']; ?>" <?php if ($categorie['id_categorie'] == $produit['id_categorie']) echo 'selected'; ?>><?php echo $categorie['nom_categorie']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <div class="form-group text-center" style="background-color: #00B2A9; color: white; border-radius: 15px; "><br>
                <label><h3>Ajouter une taille ou annuler</h3></label>
            </div>
            <?php foreach ($tailles as $taille) : ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="taille[]" value="<?php echo $taille['id_taille']; ?>" <?php foreach ($produit_tailles as $produit_taille) if ($produit_taille['id_taille'] == $taille['id_taille']) echo 'checked'; ?>>
                    <label class="form-check-label"><?php echo $taille['nom_taille']; ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <div class="form-group text-center" style="background-color: #00B2A9; color: white; border-radius: 15px; "><br>
                <label><h3>Prix des tailles</h3></label>
            </div>
            <?php foreach ($tailles as $key => $taille) : ?>
                <div class="form-group">
                    <label for="prix_taille_<?php echo $key; ?>"><?php echo $taille['nom_taille']; ?>:</label>
                    <input type="text" class="form-control" id="prix_taille_<?php echo $key; ?>" name="prix_taille[]" value="<?php foreach ($produit_tailles as $produit_taille) if ($produit_taille['id_taille'] == $taille['id_taille']) echo $produit_taille['prix_taille']; ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <div class="form-group text-center" style="background-color: #00B2A9; color: white; border-radius: 15px; "><br>
                <label><h3>Quantités des tailles</h3></label>
            </div>
            <?php foreach ($tailles as $key => $taille) : ?>
                <div class="form-group">
                    <label for="quantite_<?php echo $key; ?>"><?php echo $taille['nom_taille']; ?>:</label>
                    <input type="text" class="form-control" id="quantite_<?php echo $key; ?>" name="quantite[]" value="<?php foreach ($produit_tailles as $produit_taille) if ($produit_taille['id_taille'] == $taille['id_taille']) echo $produit_taille['quantite']; ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <div class="form-group text-center" style="background-color: #00B2A9; color: white; border-radius: 15px; "><br>
                <label><h3>Disponibilité</h3></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="disponibilite" value="1" <?php if ($produit['disponibilite'] == 1) echo 'checked'; ?>>
                <label class="form-check-label">Disponible</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="non_disponible" value="1" <?php if ($produit['disponibilite'] == 0) echo 'checked'; ?>>
                <label class="form-check-label">Non disponible</label>
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn " style="background-color: #21BA45; color: white; border-radius: 15px; ">
                           <i class="bi bi-arrow-clockwise"></i> Mettre à jour
                        </button>
        <a href="gestion_produit.php" class="btn btn-secondary" style=" color: white; border-radius: 15px; ">Annuler</a>
    </form>
</div>
<br>
