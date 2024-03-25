<?php require "includes/dbh.inc.php"; ?>

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
<?php require "header.php"; ?>

    <?php
    // Vérification si l'utilisateur est connecté et a le rôle "2"
    if (isset($_SESSION['user_id']) && $_SESSION['role'] == 2) {
        // L'utilisateur est connecté et a le rôle "2"
        ?>
                    <br>
                    <div class="container py-5">
                        <div class="mb-4">
                            <form method="GET" action="">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="Rechercher par nom d'utilisateur">
                                    <input type="date" class="form-control" name="date" placeholder="Rechercher par date" format="dd-mm-yyyy">
                                    <div class="input-group-append">
                                    <button class="btn btn" type="submit">
                        <i class="bi bi-search" style="color: black;"></i>
                    </button>

                                    </div>
                                </div>
                            </form>
                        </div>
        <?php
        $sql = "SELECT commande.id_commande, commande.date, commande.adresse, commande.nom, commande.telephone, commande.commentaire, commande.statut, users.uidUsers AS nom_utilisateur
                FROM commande
                INNER JOIN users ON commande.id_user = users.user_id";

        $search = $_GET['search'] ?? '';
        $date = $_GET['date'] ?? '';

        if (!empty($search) && !empty($date)) {
            $formattedDate = date('d-m-Y', strtotime($date));
            $sql .= " WHERE users.uidUsers LIKE '%$search%' AND DATE_FORMAT(commande.date, '%d-%m-%Y') = '$formattedDate'";
        } elseif (!empty($search)) {
            $sql .= " WHERE users.uidUsers LIKE '%$search%'";
        } elseif (!empty($date)) {
            $formattedDate = date('d-m-Y', strtotime($date));
            $sql .= " WHERE DATE_FORMAT(commande.date, '%d-%m-%Y') = '$formattedDate'";
        }

        $sql .= " ORDER BY commande.date DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt_details = null;

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="card mb-4">
                <div class="card-header" style="background-color: #00B2A9; color: white;">
                        <h5 class="card-title">Commande ID: <?php echo $row['id_commande']; ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Date:</strong> <?php echo $row['date']; ?></p>
                                <p><strong>Adresse:</strong> <?php echo $row['adresse']; ?></p>
                                <p><strong>Nom:</strong> <?php echo $row['nom']; ?></p>
                                <p><strong>Téléphone:</strong> 0<?php echo str_pad($row['telephone'], 9, '0', STR_PAD_LEFT); ?></p>
                                <p><strong>Commentaires:</strong> <?php echo $row['commentaire']; ?></p>
                                <p><strong>Nom Utilisateur:</strong> <?php echo $row['nom_utilisateur']; ?></p>
                                <p><strong>Statut:</strong> <?php echo $row['statut']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $id_commande = $row['id_commande'];
                                $sql_details = "SELECT produits.nom_produit, produit_commande.quantite, produit_commande.prix_unitaire,
                                                produit_commande.taille
                                                FROM produit_commande
                                                INNER JOIN produits ON produit_commande.id_produit = produits.id_produit
                                                WHERE produit_commande.id_commande = ?";
                                $stmt_details = $conn->prepare($sql_details);
                                $stmt_details->bind_param("i", $id_commande);
                                $stmt_details->execute();
                                $result_details = $stmt_details->get_result();
                                ?>
                                <table class="table table-bordered table-hover">
                                    <thead  style="background-color: #00B2A9; color: white ;">
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
                                echo '<p class="card-text"><strong>Total de la commande:</strong> ' . $total_commande . ' Da</p>';
                                ?>
                              <form action="update_status.php" method="post">
                              <div class="d-flex">
                                    <form action="update_status.php" method="post">
                                        <input type="hidden" name="commande_id" value="<?php echo $row['id_commande']; ?>">
                                        <button type="submit" name="annuler" class="btn btn-danger bi bi-x-circle-fill me-2"> Annuler</button>
                                        <button type="submit" name="approuver" class="btn btn bi bi-eye-fill" style="background-color: #21BA45; color: white;"> Approuver</button>
                                    </form>
                                    <form action="facture_generator.php" method="post" target="_blank" class="ms-2">
                                        <input type="hidden" name="commande_id" value="<?php echo $row['id_commande']; ?>">
                                        <button type="submit" name="generer_facture" class="btn btn-success">
                                        <i class="bi bi-file-earmark-pdf-fill me-1" style="font-size: 1em;"></i> Imprimer
                                </button>
                                    </form>
                                </div>
                                <?php
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            if (empty($search) && empty($date)) {
                echo '<p class="text-center">Veuillez saisir un nom d\'utilisateur ou une date pour effectuer une recherche.</p>';
            } else {
                echo '<p class="text-center">Aucune commande trouvée pour le nom d\'utilisateur: ' . htmlspecialchars($search) . ' et la date: ' . htmlspecialchars($date) . '</p>';
            }
        }

        if ($stmt) {
            $stmt->close();
        }
        if ($stmt_details) {
            $stmt_details->close();
        }
    } else {
        // L'utilisateur n'est pas connecté ou n'a pas le rôle "2"
        echo '<p class="text-center"></p>';
    }

    $conn->close();
    ?>
</div>
