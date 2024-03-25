<?php
// Connexion à la base de données
$conn = mysqli_connect('localhost', 'root', '', 'liverpool');
if (mysqli_connect_errno()) {
    echo "Erreur de connexion à la base de données : " . mysqli_connect_error();
    exit;
}

// Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si l'utilisateur est connecté
    session_start();
    if (isset($_SESSION['user_id'])) {
        // Récupération des données du formulaire
        $user_id = $_SESSION['user_id'];
        $comment_content = $_POST['comment_content'];
        $rating = $_POST['rating'];

        // Insertion du commentaire dans la table des commentaires
        $insert_comment = mysqli_prepare($conn, 'INSERT INTO comments (user_id, comment_content, rating) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($insert_comment, 'iss', $user_id, $comment_content, $rating);
        mysqli_stmt_execute($insert_comment);

        // Redirection vers la même page pour afficher les commentaires à jour
        header('Location: index.php');
        exit;
    }
}

try {
    // Récupération des commentaires avec les noms d'utilisateur depuis la table des commentaires et la table des utilisateurs
    $select_comments = "SELECT comments.*, users.uidUsers AS username FROM comments INNER JOIN users ON comments.user_id = users.user_id";
    $comments_result = mysqli_query($conn, $select_comments);
    $comments = mysqli_fetch_all($comments_result, MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "Erreur lors de la récupération des commentaires : " . $e->getMessage();
}

?>



