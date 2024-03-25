<?php
// Connexion à la base de données
$connexion = new PDO('mysql:host=localhost;dbname=liverpool;charset=utf8', 'root', '');

// Récupération des catégories disponibles depuis la table des catégories
$select_categories = $connexion->query('SELECT * FROM categories');
$categories = $select_categories->fetchAll(PDO::FETCH_ASSOC);

// Récupération des tailles disponibles depuis la table des tailles
$select_tailles = $connexion->query('SELECT * FROM tailles');
$tailles = $select_tailles->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du produit
    $nom_produit = $_POST['nom_produit'];
    $description = $_POST['description'];
    $image_nom = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $categorie = $_POST['categorie']; // Ajout de la catégorie

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

    // Insertion du produit dans la table des produits
    $insert_produit = $connexion->prepare('INSERT INTO produits (nom_produit, description, image, id_categorie, disponibilite, statut) VALUES (?, ?, ?, ?, "1", "actif")');
    $insert_produit->execute(array($nom_produit, $description, $image_nom, $id_categorie));

    // Récupération de l'id du produit inséré
    $id_produit = $connexion->lastInsertId();

    // Traitement des tailles
    $tailles = $_POST['taille'];
    $prix_tailles = $_POST['prix_taille'];
    $quantites = $_POST['quantite'];

    // Insertion des tailles et de leurs prix et quantités associées dans la table produits_taille
    $insert_produit_taille = $connexion->prepare('INSERT INTO produits_tailles (id_produit, id_taille, prix_taille, quantite) VALUES (?, ?, ?, ?)');

    foreach ($tailles as $key => $id_taille) {
        // Insertion du lien entre le produit et la taille avec le prix et la quantité associés
        $insert_produit_taille->execute(array($id_produit, $id_taille, $prix_tailles[$key], $quantites[$key]));
    }

    // Déplacement de l'image téléchargée dans le répertoire souhaité
    move_uploaded_file($image_tmp, 'img/' . $image_nom);

    // Redirection vers une page de confirmation
    header('Location: liste_produit.php');
    exit;
}

// Inclure le fichier "header.php" après le traitement du formulaire
include 'header.php';
?>

<br><br><br>

<div class="container">
    <h2 style="text-align: center; background-color: #00B2A9; color: white; border-radius: 15px; padding: 10px;">Ajouter un produit</h2><br>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nom_produit">Nom du produit:</label>
                <input type="text" class="form-control" name="nom_produit" required>
            </div>
            <div class="form-group col-md-6">
                <label for="categorie">Catégorie:</label>
                <select class="form-control" name="categorie" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <?php foreach ($categories as $categorie) : ?>
                        <option value="<?php echo $categorie['nom_categorie']; ?>"><?php echo $categorie['nom_categorie']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="image">Image:</label>
                <input type="file" class="form-control" name="image" required>
            </div>
            <div class="form-group col-md-6">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
        </div>
        <div id="tailles">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="taille[]">Taille:</label>
                    <select class="form-control" name="taille[]" required>
                        <option value="">Sélectionnez une taille</option>
                        <?php foreach ($tailles as $taille) : ?>
                            <option value="<?php echo $taille['id_taille']; ?>"><?php echo $taille['nom_taille']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="prix_taille[]">Prix:</label>
                    <input type="text" class="form-control" name="prix_taille[]" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="quantite[]">Quantité:</label>
                    <input type="text" class="form-control" name="quantite[]" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-8">
                <button type="submit" style="background-color: #21BA45; color: white; " class="btn btn bi bi-plus-square"> Ajouter le produit</button>
                <a href="CRUD_categorie.php" class="btn btn-warning bi bi-pencil-square"> Modifier une catégorie</a>
                <a href="crud_taille.php" class="btn btn-warning bi bi-pencil-square"> Modifier une taille</a>
            </div>
            <div class="form-group col-md-4">
                <button type="button"  class="btn btn-primary bi bi-plus-square" onclick="ajouterTaille()"> Ajouter une taille</button>
                <button type="button" class="btn btn-danger bi bi-trash" onclick="supprimerDerniereTaille()"> Supprimer une taille</button>

            </div>
        </div>
    </form>
</div>

<!-- Liens vers les fichiers JavaScript Bootstrap -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- Script pour ajouter dynamiquement des champs pour les tailles -->
<script>
    function ajouterTaille() {
        var tailleDiv = document.createElement('div');
        tailleDiv.classList.add('form-row');

        var selectDiv = document.createElement('div');
        selectDiv.classList.add('form-group', 'col-md-4');

        var selectLabel = document.createElement('label');
        selectLabel.setAttribute('for', 'taille[]');
        selectLabel.textContent = 'Taille:';

        var selectInput = document.createElement('select');
        selectInput.classList.add('form-control');
        selectInput.setAttribute('name', 'taille[]');
        selectInput.required = true;

        var defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Sélectionnez une taille';

        selectInput.appendChild(defaultOption);

        <?php foreach ($tailles as $taille) : ?>
            var option = document.createElement('option');
            option.value = '<?php echo $taille['id_taille']; ?>';
            option.textContent = '<?php echo $taille['nom_taille']; ?>';
            selectInput.appendChild(option);
        <?php endforeach; ?>

        selectDiv.appendChild(selectLabel);
        selectDiv.appendChild(selectInput);

        var prixDiv = document.createElement('div');
        prixDiv.classList.add('form-group', 'col-md-4');

        var prixLabel = document.createElement('label');
        prixLabel.setAttribute('for', 'prix_taille[]');
        prixLabel.textContent = 'Prix:';

        var prixInput = document.createElement('input');
        prixInput.classList.add('form-control');
        prixInput.setAttribute('type', 'text');
        prixInput.setAttribute('name', 'prix_taille[]');
        prixInput.required = true;

        prixDiv.appendChild(prixLabel);
        prixDiv.appendChild(prixInput);

        var quantiteDiv = document.createElement('div');
        quantiteDiv.classList.add('form-group', 'col-md-4');

        var quantiteLabel = document.createElement('label');
        quantiteLabel.setAttribute('for', 'quantite[]');
        quantiteLabel.textContent = 'Quantité:';

        var quantiteInput = document.createElement('input');
        quantiteInput.classList.add('form-control');
        quantiteInput.setAttribute('type', 'text');
        quantiteInput.setAttribute('name', 'quantite[]');
        quantiteInput.required = true;

        quantiteDiv.appendChild(quantiteLabel);
        quantiteDiv.appendChild(quantiteInput);

        tailleDiv.appendChild(selectDiv);
        tailleDiv.appendChild(prixDiv);
        tailleDiv.appendChild(quantiteDiv);

        document.getElementById('tailles').appendChild(tailleDiv);
        
    }
    function supprimerDerniereTaille() {
        var taillesDiv = document.getElementById('tailles');
        var dernierTailleDiv = taillesDiv.lastElementChild;

        if (dernierTailleDiv !== null) {
            taillesDiv.removeChild(dernierTailleDiv);
        }
    }
</script>
