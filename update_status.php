<?php
require "includes/dbh.inc.php";

if (isset($_POST['approuver'])) {
    $commande_id = $_POST['commande_id'];

    // Mettre à jour le statut de la commande en "Approuvée"
    $sql = "UPDATE commande SET statut = ' Commande approuvée  ' WHERE id_commande = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $commande_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_commande.php"); // Rediriger vers la page de liste des commandes après la mise à jour du statut
    exit();
} elseif (isset($_POST['annuler'])) {
    $commande_id = $_POST['commande_id'];

    // Mettre à jour le statut de la commande en "Annulée"
    $sql = "UPDATE commande SET statut = 'Commande annulée' WHERE id_commande = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $commande_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_commande.php"); // Rediriger vers la page de liste des commandes après la mise à jour du statut
    exit();
} else {
    // Redirection en cas d'accès direct à ce fichier sans soumettre le formulaire
    header("Location: admin_commande.php");
    exit();
}
?>



