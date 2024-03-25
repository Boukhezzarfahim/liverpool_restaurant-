<?php
session_start();
require "includes/dbh.inc.php";

// Vérifier si l'utilisateur est connecté et n'a pas le rôle d'administration
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

// Traitement de la suppression de commentaire
$deleteMessage = "";
if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];

    // Effectuer la suppression du commentaire dans la base de données
    $delete_comment_query = $conn->prepare('DELETE FROM comments WHERE comment_id = ?');
    $delete_comment_query->bind_param('i', $comment_id);
    $delete_comment_query->execute();

    // Message de succès pour l'alerte
    $deleteMessage = "Le commentaire a été supprimé avec succès.";
}

try {
    // Récupération des commentaires avec les noms d'utilisateur depuis la table des commentaires et la table des utilisateurs
    $select_comments = $conn->prepare('SELECT comments.*, users.uidUsers AS username FROM comments INNER JOIN users ON comments.user_id = users.user_id ORDER BY comments.comment_id DESC');
    $select_comments->execute();
    $result = $select_comments->get_result();

    $comments = array();
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des commentaires : " . $e->getMessage();
}
?>

<?php require "header.php"; ?>
<br><br><br>
<div class="container">
  <h2 style="text-align: center; border-radius: 15px;  background-color: #00B2A9; color: white; padding: 5px;">Gestion des commentaires</h2><br>
  <?php if (!empty($deleteMessage)) { ?>
    <div class="alert alert-success" role="alert">
      <?php echo $deleteMessage; ?>
    </div>
  <?php } ?>
  <table class="table">
    <thead style="background-color: #00B2A9; color: white;">
      <tr>
        <th>Utilisateur</th>
        <th>Commentaire</th>
        <th>Note</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($comments as $comment) { ?>
        <tr>
          <td><?php echo $comment['username']; ?></td>
          <td><?php echo $comment['comment_content']; ?></td>
          <td><?php echo $comment['rating']; ?></td>
          <td>
            <form action="gestion_commentaires.php" method="POST">
              <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
              <button type="submit" name="delete_comment" class="btn btn-danger bi bi-trash"> Supprimer</button>
            </form>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
