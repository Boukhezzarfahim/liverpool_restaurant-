<?php
$servername = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "liverpool";

$connexion = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$connexion) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($connexion, "utf8");

$mode_ajout = true; // Variable pour contrôler l'affichage du formulaire d'ajout

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'categorie') {
        if (isset($_POST['id_categorie'])) {
            // Mode modification - Traitement de la modification de la catégorie
            $id_categorie = $_POST['id_categorie'];
            $nom_categorie = isset($_POST['nom_categorie']) ? $_POST['nom_categorie'] : '';

            $update_categorie = $connexion->prepare('UPDATE categories SET nom_categorie = ? WHERE id_categorie = ?');
            $update_categorie->bind_param('si', $nom_categorie, $id_categorie);
            if ($update_categorie->execute()) {
                header("Location: CRUD_categorie.php");
                // Modification réussie, effectuer d'autres actions si nécessaire
            } else {
                echo "Erreur lors de la modification de la catégorie : " . $update_categorie->error;
            }
        } else {
            // Mode ajout - Traitement de l'ajout de la catégorie
            $nom_categorie = isset($_POST['nom_categorie']) ? $_POST['nom_categorie'] : '';

            // Vérifier si le champ "Ajouter une catégorie" est vide
            if (!empty($nom_categorie)) {
                $insert_categorie = $connexion->prepare('INSERT INTO categories (nom_categorie) VALUES (?)');
                $insert_categorie->bind_param('s', $nom_categorie);
                if ($insert_categorie->execute()) {
                    // Ajout réussi, effectuer d'autres actions si nécessaire
                    header("Location: CRUD_categorie.php");
                    exit;
                } else {
                    echo "Erreur lors de l'ajout de la catégorie : " . $insert_categorie->error;
                }
            } else {
                echo "Le champ 'Ajouter une catégorie' ne peut pas être vide.";
            }
        }
    } elseif ($action === 'supprimer_categorie') {
        // Traitement de la suppression de la catégorie
        $id_categorie = isset($_POST['id_categorie']) ? $_POST['id_categorie'] : '';

        // Vérifier si la catégorie est utilisée dans d'autres tables liées
        // ... Effectuer la vérification appropriée ...

        $delete_categorie = $connexion->prepare('DELETE FROM categories WHERE id_categorie = ?');
        if ($delete_categorie) {
            $delete_categorie->bind_param('i', $id_categorie);
            if ($delete_categorie->execute()) {
                if ($delete_categorie->affected_rows > 0) {
                    header("Location: CRUD_categorie.php");
                    // Suppression réussie, effectuer d'autres actions si nécessaire
                } else {
                    echo "La catégorie est associée à des produits. La suppression est impossible.";
                }
            }
                }
            }
         }

if (isset($_POST['action']) && $_POST['action'] === 'modifier_categorie' && isset($_POST['id_categorie'])) {
    // Affichage du formulaire d'édition pour la modification d'une catégorie
    $mode_ajout = false;

    $id_categorie = $_POST['id_categorie'];

    $select_categorie = $connexion->prepare('SELECT * FROM categories WHERE id_categorie = ?');
    if ($select_categorie) {
        $select_categorie->bind_param('i', $id_categorie);
        if ($select_categorie->execute()) {
            $result_categorie = $select_categorie->get_result();
            $categorie = $result_categorie->fetch_assoc();
        } else {
            echo "Erreur lors de la récupération de la catégorie : " . $select_categorie->error;
        }
    }
}
?>

<?php require "header.php"; ?>
<br><br><br>
<div class="container">
<h2 style="text-align: center; background-color: #00B2A9; color: white; border-radius: 15px ; padding: 5px;">Gestion des catégories</h2>


    <form action="" method="POST">
        <input type="hidden" name="action" value="categorie">
        <?php if (!$mode_ajout && isset($categorie)) : ?>
            <!-- Formulaire d'édition d'une catégorie existante -->
            <input type="hidden" name="id_categorie" value="<?php echo $categorie['id_categorie']; ?>">
            <div class="form-group">
                <label for="nom_categorie">Nom de la catégorie :</label>
                <input type="text" class="form-control" id="nom_categorie" name="nom_categorie" value="<?php echo $categorie['nom_categorie']; ?>">
            </div>
            <button class="btn btn-warning bi bi-pencil-fill" type="submit"> Modifier la catégorie</button>
            <button class="btn btn-secondary bi bi-x " type="button" onclick="cancelEditCategorie()">Annuler</button>
        <?php else : ?>
            <!-- Formulaire d'ajout d'une nouvelle catégorie -->
            <div class="form-group">
                <label for="nom_categorie">Nom de la catégorie :</label>
                <input type="text" class="form-control" id="nom_categorie" name="nom_categorie">
            </div>
            <button class="btn btn bi bi-plus" style="background-color: #21BA45; color: white;" type="submit">Ajouter la catégorie</button>
        <?php endif; ?>
    </form>
    <br>

    <!-- Liste des catégories existantes -->
    <h3 style="text-align: center; background-color: #00B2A9; color: white; border-radius: 15px ; padding: 5px;">Liste des catégories</h3>
    <table class="table">
        <thead class="" style="background-color: #00B2A9; color: white; ">
        <tr>
            <th>ID</th>
            <th>Nom de la catégorie</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Récupération des catégories depuis la table "categories"
        $select_categories = $connexion->query('SELECT * FROM categories');
        while ($categorie = $select_categories->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $categorie['id_categorie'] . "</td>";
            echo "<td>" . $categorie['nom_categorie'] . "</td>";
            echo "<td>
                        <form action='' method='POST' style='display:inline'>
                            <input type='hidden' name='action' value='modifier_categorie'>
                            <input type='hidden' name='id_categorie' value='" . $categorie['id_categorie'] . "'>
                            <button class='btn btn-warning bi bi-pencil-fill' type='submit'> Modifier</button>
                        </form>
                        <form action='' method='POST' style='display:inline'>
                            <input type='hidden' name='action' value='supprimer_categorie'>
                            <input type='hidden' name='id_categorie' value='" . $categorie['id_categorie'] . "'>
                            <button class='btn btn-danger bi bi-trash' type='submit'> Supprimer </button>
                        </form>
                  </td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <a href="traitement_ajout_produit.php" class="btn btn-primary bi bi-arrow-left">Retour</a>
</div>

<script>
    function cancelEditCategorie() {
        window.location.href = "CRUD_categorie.php";
    }
</script>

<br>


