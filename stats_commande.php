<?php
require "includes/dbh.inc.php";
require "header.php";
?>

<br><br><br>

<style>
    .bg-green {
        background-color: #00B2A9;
        color: white;
    }
</style>

<div class="container">
    <?php
    // Obtention du mois actuel
    $mois = date('F Y'); // Exemple: "May 2023"

    echo '<h1 style="text-align: center; background-color: #00B2A9; color: white; border-radius: 15px; padding: 5px;">Statistiques des commandes</h1>';


    // Conversion du mois en format compatible avec MySQL (ex: 2023-05)
    $mois_mysql = date('m-Y', strtotime($mois));

    // Requête SQL pour obtenir l'historique des mois
    $sql_historique = "SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') AS mois
                    FROM commande
                    ORDER BY mois DESC";

    $result_historique = $conn->query($sql_historique);

    if ($result_historique && $result_historique->num_rows > 0) {
        echo '<h3>Historique des mois</h3>';
        echo '<ul>';

        while ($row_historique = $result_historique->fetch_assoc()) {
            $mois_row = date('F Y', strtotime($row_historique['mois']));
            echo '<li><a href="?mois=' . $row_historique['mois'] . '" style="text-decoration: none;">' . $mois_row . '</a></li>';

        }

        echo '</ul>';
    } else {
        echo '<p ">Aucun historique de mois disponible.</p>';
    }

    // Vérifier si un mois est sélectionné
    if (isset($_GET['mois'])) {
        // Récupérer le mois sélectionné depuis l'URL
        $mois_selectionne = $_GET['mois'];

        // Conversion du mois sélectionné en format compatible avec MySQL (ex: 2023-05)
        $mois_mysql = date('Y-m', strtotime($mois_selectionne));

        echo '<h2 style="text-align: center; background-color: #00B2A9; border-radius: 15px;  color: white;">Statistiques des commandes pour le mois de ' . $mois_selectionne . '</h2>';

        // Requête SQL pour les statistiques des commandes annulées et approuvées pour le mois sélectionné
        $sql_stats = "SELECT DATE(date) AS jour,
                        SUM(CASE WHEN statut = 'Commande annulée' THEN 1 ELSE 0 END) AS commandes_annulees,
                        SUM(CASE WHEN statut = ' Commande approuvée ' THEN 1 ELSE 0 END) AS commandes_approuvees
                    FROM commande
                    WHERE DATE_FORMAT(date, '%Y-%m') = ?
                    GROUP BY DATE(date)
                    ORDER BY DATE(date) DESC";

        $stmt_stats = $conn->prepare($sql_stats);
        $stmt_stats->bind_param("s", $mois_mysql);

        // Exécution de la requête pour le mois sélectionné
        $stmt_stats->execute();
        $result_stats = $stmt_stats->get_result();

        if ($result_stats && $result_stats->num_rows > 0) {
            // Affichage des statistiques
            echo '<table class="table">';
            echo '<thead class="bg-green">';
            echo '<tr><th>Jour</th><th>Commandes annulées</th><th>Commandes approuvées</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($row_stats = $result_stats->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row_stats['jour'] . '</td>';
                echo '<td>' . $row_stats['commandes_annulees'] . '</td>';
                echo '<td>' . $row_stats['commandes_approuvees'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p style="text-align: center;">Aucune statistique de commande trouvée pour le mois ' . $mois_selectionne . '.</p>';
        }

        // Fermeture du statement
        $stmt_stats->close();
    } else {
        echo '<p style="text-align: center;">Sélectionnez un mois dans l\'historique ci-dessus pour afficher les statistiques correspondantes.</p>';
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
    ?>
</div>


