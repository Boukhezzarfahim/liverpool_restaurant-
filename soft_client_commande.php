<?php require "includes/dbh.inc.php"; ?>
<?php require "header.php"; ?>

<style>
    body {
        font-size: 18px;
        font-family: "Arial", sans-serif;
    }

    h5.card-title {
        font-size: 24px;
        font-weight: bold;
    }

    p.card-text {
        font-size: 16px;
    }

    table.table {
        font-size: 16px;
    }
</style>

<br>
<div class="container py-5">
    <?php
    // Vérifier si l'utilisateur est connecté et a un rôle égal à 1
    if (isset($_SESSION['user_id']) && $_SESSION['role'] == 1) {
        ?>
        <!-- Formulaire de recherche -->
        <div class="mb-4">
            <form method="GET" action="">
                <div class="input-group">
                    <input type="date" class="form-control" name="date" placeholder="Rechercher par date">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php

        // Préparer la requête SQL
        $sql = "SELECT commande.id_commande, commande.date, commande.adresse, commande.nom, commande.telephone, commande.commentaire, commande.statut, commande.etat, users.uidUsers AS nom_utilisateur
                FROM commande
                INNER JOIN users ON commande.id_user = users.user_id";

        // Récupérer la valeur de recherche par date à partir du paramètre GET
        $date = $_GET['date'] ?? '';

        // Ajouter une condition à la requête SQL en fonction de la valeur de la date
        if (!empty($date)) {
            $formattedDate = date('Y-m-d', strtotime($date));
            $sql .= " WHERE DATE(commande.date) = '$formattedDate'";
        } else {
            $sql .= " WHERE commande.etat != 'inactif'";
        }

        // Trier les résultats par date dans l'ordre décroissant
        $sql .= " ORDER BY commande.date DESC";

        // Préparer et exécuter la requête SQL
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['etat'] != 'inactif') {
                    ?>
                    <div class="card mb-4">
                        <div class="card-header text-white"  style="background-color: #00B2A9;">
                            <h5 class="card-title">Commande ID: <?php echo $row['id_commande']; ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Afficher les détails de la commande -->
                                    <p><strong>Date:</strong> <?php echo $row['date']; ?></p>
                                    <p><strong>Adresse:</strong> <?php echo $row['adresse']; ?></p>
                                    <p><strong>Nom:</strong> <?php echo $row['nom']; ?></p>
                                    <p><strong>Téléphone:</strong> 0<?php echo str_pad($row['telephone'], 9, '0', STR_PAD_LEFT); ?></p>
                                    <p><strong>Commentaires:</strong> <?php echo $row['commentaire']; ?></p>
                                    <p><strong>Nom Utilisateur:</strong> <?php echo $row['nom_utilisateur']; ?></p>
                                    <p><strong>Etat de commande:</strong> <?php echo $row['statut']; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    // Récupérer les détails de la commande
                                    $id_commande = $row['id_commande'];
                                    $sql_details = "SELECT produits.nom_produit, produit_commande.quantite, produit_commande.prix_unitaire, produit_commande.taille
                                                    FROM produit_commande
                                                    INNER JOIN produits ON produit_commande.id_produit = produits.id_produit
                                                    WHERE produit_commande.id_commande = ?";
                                    $stmt_details = $conn->prepare($sql_details);
                                    $stmt_details->bind_param("i", $id_commande);
                                    $stmt_details->execute();
                                    $result_details = $stmt_details->get_result();
                                    ?>
                                    <table class="table table-bordered table-hover">
                                        <thead class="opacity-75 text-white" style="background-color: #00B2A9;">
                                        <tr>
                                            <th scope="col">Produit</th>
                                            <th scope="col">Quantité</th>
                                            <th scope="col">Prix unitaire</th>
                                            <th scope="col">Taille</th>
                                            <th scope="col">Prix total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $total_commande = 0;
                                        while ($row_details = $result_details->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row_details['nom_produit'] !== null ? $row_details['nom_produit'] : 'N/A'; ?></td>
                                                <td><?php echo $row_details['quantite']; ?></td>
                                                <td><?php echo $row_details['prix_unitaire']; ?></td>
                                                <td><?php echo $row_details['taille']; ?></td>
                                                <?php
                                                if ($row_details['nom_produit'] !== null) {
                                                    $prix_total = $row_details['quantite'] * $row_details['prix_unitaire'];
                                                    echo '<td>' . $prix_total . '</td>';
                                                    $total_commande += $prix_total;
                                                } else {
                                                    echo '<td>N/A</td>';
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>

                                    <?php
                                    // Afficher le montant total de la commande
                                    echo '<p class="card-text"><strong>Total de la commande:</strong> ' . $total_commande . ' Da</p>';
                                    ?>

                                    <form action="soft_delete.php" method="post">
                                        <input type="hidden" name="commande_id" value="<?php echo $row['id_commande']; ?>">
                                        <?php
                                        // Vérifier si la commande est supprimée ou non
                                        $is_deleted = !$row['etat'];

                                        if (!$is_deleted) {
                                            // Afficher le bouton de suppression si la commande n'est pas supprimée
                                            echo '<button type="submit" name="soft_delete" class="btn btn-danger bi bi-trash">Supprimer l\'historique </button>';
                                        } 
                                        ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        } else {
            // Afficher un message si aucune commande n'est trouvée
            echo '<p class="text-center">Aucune commande trouvée.</p>';
        }
    } else {
        // Rediriger vers une page d'erreur ou afficher un message d'erreur si l'utilisateur n'est pas connecté avec un rôle égal à 1
        echo '<p class="text-center">Accès refusé. Veuillez vous connecter en tant qu\'utilisateur administrateur.</p>';
    }
    ?>

</div>

<?php require "footer.php"; ?>
