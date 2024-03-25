<?php
require __DIR__ . "/tcpdf/tcpdf.php";

function generer_facture($commande_id) {
    require "includes/dbh.inc.php";
    // Récupérer les détails de la commande
    $sql_commande = "SELECT commande.id_commande, commande.date, commande.adresse, commande.telephone, users.uidUsers AS nom_utilisateur
                     FROM commande
                     INNER JOIN users ON commande.id_user = users.user_id
                     WHERE commande.id_commande = ?";
    $stmt_commande = $conn->prepare($sql_commande);
    $stmt_commande->bind_param("i", $commande_id);
    $stmt_commande->execute();
    $result_commande = $stmt_commande->get_result();
    $row_commande = $result_commande->fetch_assoc();

    // Récupérer les détails des produits de la commande
    $sql_details = "SELECT produits.nom_produit, produit_commande.quantite, produit_commande.prix_unitaire, produit_commande.taille
                    FROM produit_commande
                    INNER JOIN produits ON produit_commande.id_produit = produits.id_produit
                    WHERE produit_commande.id_commande = ?";
    $stmt_details = $conn->prepare($sql_details);
    $stmt_details->bind_param("i", $commande_id);
    $stmt_details->execute();
    $result_details = $stmt_details->get_result();

    // Créez une nouvelle instance de TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Définir les métadonnées du document PDF
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Votre nom');
    $pdf->SetTitle('Facture de commande');
    $pdf->SetSubject('Facture de commande');
    $pdf->SetKeywords('facture, commande');

    // Set margins
    $pdf->SetMargins(15, 15, 15, true);

    // Ajoutez une page
    $pdf->AddPage();

  

    // Rétablir la taille de police par défaut
    $pdf->SetFont('helvetica', '', 12);

    // Ajouter le logo du restaurant
    $logo_path = 'img/logo_liverpool-1-removebg-preview.png';
    $pdf->Image($logo_path, 160, 10, 38, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false);

    // Ajouter les informations du restaurant
    $pdf->Ln(15);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, '153 Av. Mustapha Ali Khodja, El Biar 16000', 0, 1, 'L');
    $pdf->Cell(0, 10, 'Liverpool@gmail.com', 0, 1, 'L');

$pdf->Cell(0, 10, 'Téléphone: 0' . $row_commande['telephone'], 0, 1, 'L');

// Ajouter une ligne en gras
$pdf->SetLineStyle(array('width' => 0.5, 'color' => array(0, 0, 0))); // Définir les propriétés de la ligne
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 0, '', 'B', 1, 'L'); // Dessiner une ligne en gras


 // Définir la taille de police pour le titre
 $pdf->SetFont('helvetica', '', 15);
 $pdf->Cell(0, 10, 'Facture de commande', 0, 1, 'C');

    // Ajouter les détails de la commande
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Commande ID: ' . $row_commande['id_commande'], 0, 1, 'L');
    $pdf->Cell(0, 10, 'Date: ' . $row_commande['date'], 0, 1, 'L');
    $pdf->Cell(0, 10, 'Adresse: ' . $row_commande['adresse'], 0, 1, 'L');
    $pdf->Cell(0, 10, 'Téléphone: 0' . $row_commande['telephone'], 0, 1, 'L');
    $pdf->Cell(0, 10, 'Nom Utilisateur: ' . $row_commande['nom_utilisateur'], 0, 1, 'L');

    // Ajouter une table des détails des produits
    $pdf->Ln(10);
    $pdf->SetFillColor(220, 220, 220); // Couleur de fond de l'en-tête de la table
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 10, 'Produit', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Quantité', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Prix unitaire', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Taille', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Prix total', 1, 1, 'C', true);

    $pdf->SetFont('helvetica', '', 12);
    $pdf->SetFillColor(255, 255, 255); // Couleur de fond des cellules de données

    $total_commande = 0;
    while ($row_details = $result_details->fetch_assoc()) {
        $pdf->Cell(40, 10, $row_details['nom_produit'] !== null ? $row_details['nom_produit'] : 'N/A', 1, 0, 'L', true);
        $pdf->Cell(30, 10, $row_details['quantite'], 1, 0, 'C', true);
        $pdf->Cell(30, 10, $row_details['prix_unitaire'], 1, 0, 'C', true);
        $pdf->Cell(30, 10, $row_details['taille'], 1, 0, 'C', true);

        if ($row_details['nom_produit'] !== null) {
            $prix_total = $row_details['quantite'] * $row_details['prix_unitaire'];
            $pdf->Cell(40, 10, $prix_total, 1, 1, 'C', true);
            $total_commande += $prix_total;
        } else {
            $pdf->Cell(40, 10, 'N/A', 1, 1, 'C', true);
        }
    }

    // Ajouter le total de la commande
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Total de la commande: ' . $total_commande . ' Da', 0, 1, 'R');

    // Générer le fichier PDF
    ob_clean();
    $pdf->Output('facture_commande_' . $row_commande['id_commande'] . '.pdf', 'I');
}

// Vérifier si la commande_id est présente dans la requête POST
if (isset($_POST['commande_id'])) {
    $commande_id = $_POST['commande_id'];
    generer_facture($commande_id);
}
?>
