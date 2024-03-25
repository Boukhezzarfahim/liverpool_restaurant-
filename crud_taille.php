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

$mode_ajout = true;
$taille = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'taille') {
        // Ajouter ou modifier une taille
        if (isset($_POST['id_taille'])) {
            // Modification d'une taille existante
            $id_taille = $_POST['id_taille'];
            $nom_taille = $_POST['nom_taille'];

            $update_taille = $connexion->prepare('UPDATE tailles SET nom_taille = ? WHERE id_taille = ?');
            if ($update_taille) {
                $update_taille->bind_param('si', $nom_taille, $id_taille);
                if ($update_taille->execute()) {
                    if ($update_taille->affected_rows > 0) {
                        header("Location: crud_taille.php");
                        // Modification réussie, effectuer d'autres actions si nécessaire
                        exit;
                    } else {
                        echo "La taille n'existe pas ou a déjà été supprimée.";
                    }
                } else {
                    echo "Erreur lors de la modification de la taille : " . $update_taille->error;
                }
            } else {
                echo "Erreur lors de la préparation de la requête de modification : " . $connexion->error;
            }
        } else {
            // Ajout d'une nouvelle taille
            $nom_taille = $_POST['nom_taille'];

            if (!empty($nom_taille)) {
                $insert_taille = $connexion->prepare('INSERT INTO tailles (nom_taille) VALUES (?)');
                if ($insert_taille) {
                    $insert_taille->bind_param('s', $nom_taille);
                    if ($insert_taille->execute()) {
                        if ($insert_taille->affected_rows > 0) {
                            header("Location: CRUD_taille.php");
                            // Ajout réussi, effectuer d'autres actions si nécessaire
                            exit;
                        } else {
                            echo "Erreur lors de l'ajout de la taille.";
                        }
                    } else {
                        echo "Erreur lors de l'ajout de la taille : " . $insert_taille->error;
                    }
                } else {
                    echo "Erreur lors de la préparation de la requête d'ajout : " . $connexion->error;
                }
            } else {
                echo "";
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'supprimer_taille') {
        // Supprimer une taille
        if (isset($_POST['id_taille'])) {
            $id_taille = $_POST['id_taille'];

            // Vérifier si la taille est utilisée dans la table intermédiaire produits_tailles
            $check_produits_tailles = $connexion->prepare('SELECT COUNT(*) AS count FROM produits_tailles WHERE id_taille = ?');
            if ($check_produits_tailles) {
                $check_produits_tailles->bind_param('i', $id_taille);
                if ($check_produits_tailles->execute()) {
                    $result = $check_produits_tailles->get_result();
                    $row = $result->fetch_assoc();
                    $count = $row['count'];
                    if ($count > 0) {
                        echo "";
                    } else {
                        // Supprimer la taille de la table tailles
                        $delete_taille = $connexion->prepare('DELETE FROM tailles WHERE id_taille = ?');
                        if ($delete_taille) {
                            $delete_taille->bind_param('i', $id_taille);
                            if ($delete_taille->execute()) {
                                if ($delete_taille->affected_rows > 0) {
                                    // Suppression réussie, effectuer d'autres actions si nécessaire
                                    header("Location: CRUD_taille.php");
                                    exit;
                                } else {
                                    echo "La taille n'existe pas ou a déjà été supprimée.";
                                }
                            } else {
                                echo "Erreur lors de la suppression de la taille : " . $delete_taille->error;
                            }
                        } else {
                            echo "Erreur lors de la préparation de la requête de suppression : " . $connexion->error;
                        }
                    }
                } else {
                    echo "Erreur lors de la vérification de l'utilisation de la taille : " . $check_produits_tailles->error;
                }
            } else {
                echo "Erreur lors de la préparation de la requête de vérification : " . $connexion->error;
            }
        } else {
            echo "ID de taille manquant.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'modifier_taille') {
        // Charger les informations de la taille à modifier
        if (isset($_POST['id_taille'])) {
            $id_taille = $_POST['id_taille'];

            $select_taille = $connexion->prepare('SELECT * FROM tailles WHERE id_taille = ?');
            if ($select_taille) {
                $select_taille->bind_param('i', $id_taille);
                if ($select_taille->execute()) {
                    $result_taille = $select_taille->get_result();
                    $taille = $result_taille->fetch_assoc();
                    $mode_ajout = false;
                } else {
                    echo "Erreur lors de la récupération des informations de la taille : " . $select_taille->error;
                }
            } else {
                echo "Erreur lors de la préparation de la requête de récupération : " . $connexion->error;
            }
        } else {
            echo "ID de taille manquant.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'annuler') {
        header("Location: CRUD_taille.php");
        exit;
    }
}
?>
<?php require "header.php"; ?>
<br><br><br>
<!-- Formulaire d'ajout/modification d'une taille -->
<div class="container">
    <h1 style="text-align: center; background-color: #00B2A9; color: white; border-radius: 15px; padding: 5px;">Gestion des tailles</h1>
    <form action="" method="POST">
        <?php if ($mode_ajout) : ?>
            <!-- Formulaire d'ajout d'une nouvelle taille -->
            <div class="form-group">
                <label for="nom_taille">Ajouter une taille :</label>
                <input type="text" class="form-control" id="nom_taille" name="nom_taille">
            </div>
            <button class="btn btn bi bi-plus " style="background-color: #21BA45; color: white;" type="submit" name="action" value="taille">Ajouter la taille</button>
            <button class="btn btn-secondary bi bi-x" type="submit" name="action" value="annuler"> Annuler</button>
        <?php else : ?>
            <!-- Formulaire de modification d'une taille existante -->
            <input type="hidden" name="id_taille" value="<?php echo $taille['id_taille']; ?>">
            <div class="form-group">
                <label for="nom_taille">Modifier la taille :</label>
                <input type="text" class="form-control" id="nom_taille" name="nom_taille" value="<?php echo $taille['nom_taille']; ?>">
            </div>
            <button class="btn btn-warning bi bi-pencil-fill" type="submit" name="action" value="taille"> Modifier la taille</button>
            <button class="btn btn-secondary bi bi-x" type="submit" name="action" value="annuler"> Annuler</button>
        <?php endif; ?>
    </form>
</div><br>

<!-- Tableau des tailles -->
<div class="container">
    <h2 style="text-align: center; background-color: #00B2A9; color: white; border-radius: 15px; padding: 5px;">Liste des tailles</h2>
    <table class="table">
        <thead style="background-color: #00B2A9; color: white;">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $select_tailles = $connexion->query("SELECT * FROM tailles");
            if ($select_tailles) {
                while ($taille = $select_tailles->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $taille['id_taille'] . "</td>";
                    echo "<td>" . $taille['nom_taille'] . "</td>";
                    echo "<td>";
                    echo "<form action='' method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='id_taille' value='" . $taille['id_taille'] . "'>";
                    echo "<button class='btn btn-warning' type='submit' name='action' value='modifier_taille'><i class='bi bi-pencil'></i> Modifier</button>";
                    echo "</form>";
                    echo "&nbsp;"; // Ajout d'un espace entre les boutons
                    echo "<form action='' method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='id_taille' value='" . $taille['id_taille'] . "'>";
                    echo "<button class='btn btn-danger' type='submit' name='action' value='supprimer_taille'><i class='bi bi-trash'></i> Supprimer </button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                    
                    
                    
                }
            } else {
                echo "Erreur lors de la récupération des tailles : " . $connexion->error;
            }
            ?>
        </tbody>
    </table>
    <a href="traitement_ajout_produit.php" class="btn btn-primary bi bi-arrow-left">Retour</a>
</div>

<?php
$connexion->close();
?>
<br>