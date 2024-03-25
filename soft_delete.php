<?php
// Inclure le fichier de connexion à la base de données (dbh.inc.php)
require "includes/dbh.inc.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si le bouton de suppression douce a été cliqué
    if (isset($_POST['soft_delete'])) {
        // Récupérer l'ID de la commande à supprimer
        $commande_id = $_POST['commande_id'];

        // Mettre à jour l'état de la commande en "inactif" dans la base de données
        $sql = "UPDATE commande SET etat = 'inactif' WHERE id_commande = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $commande_id);
            $stmt->execute();
            
            // Rediriger vers la page des commandes (ou afficher un message de confirmation)
            header("Location: soft_client_commande.php");
            exit();
        } else {
            // Gérer l'erreur de préparation de la requête de suppression douce
            echo "Erreur de préparation de la requête de suppression douce: " . $conn->error;
            // Faites quelque chose en cas d'échec de la préparation de la requête
        }
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>
