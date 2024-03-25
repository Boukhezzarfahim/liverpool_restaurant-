<?php
 
require "includes/dbh.inc.php";

if (isset($_GET['id'])) {
    $idProduit = $_GET['id'];

    // Mettre à jour le statut du produit à "inactif"
    $sql_update_produit = "UPDATE produits SET statut = 'inactif' WHERE id_produit = $idProduit";
    $result_update_produit = $conn->query($sql_update_produit);

    if ($result_update_produit) {
        // Rediriger l'utilisateur vers la page des produits avec un message de succès
        header("Location: gestion_produit.php?success=Le produit a été désactivé avec succès.");
        exit;
    } else {
        // Afficher un message d'erreur
        echo "Erreur lors de la désactivation du produit: " . $conn->error;
    }
} else {
    // Rediriger l'utilisateur vers la page des produits
    header("Location: gestion_produit.php");
    exit;
}
