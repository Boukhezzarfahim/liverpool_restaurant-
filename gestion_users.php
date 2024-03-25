<?php
session_start();
require 'includes/dbh.inc.php';

// Vérifier si l'utilisateur est connecté et a le rôle d'administrateur
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 2) {
    // Changer le rôle d'un utilisateur
    $changeRoleMessage = "";
    if (isset($_POST['change_role'])) {
        $user_id = $_POST['user_id'];

        // Vérifier le rôle actuel de l'utilisateur
        $select_query = "SELECT role_id FROM users WHERE user_id = $user_id";
        $result = $conn->query($select_query);
        $row = $result->fetch_assoc();
        $current_role_id = $row['role_id'];

        // Basculer entre le rôle d'administrateur et le rôle d'utilisateur
        $new_role_id = ($current_role_id == 2) ? 1 : 2;

        // Mettre à jour le rôle de l'utilisateur dans la base de données
        $update_query = "UPDATE users SET role_id = $new_role_id WHERE user_id = $user_id";
        $conn->query($update_query);

        // Message de succès pour l'alerte
        $changeRoleMessage = "Le rôle de l'utilisateur a été modifié avec succès.";
    }

    // Supprimer un utilisateur
    $deleteUserMessage = "";
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];

        // Supprimer l'utilisateur de la base de données
        $delete_query = "DELETE FROM users WHERE user_id = $user_id";
        $conn->query($delete_query);

        // Message de succès pour l'alerte
        $deleteUserMessage = "L'utilisateur a été supprimé avec succès.";
    }

    // Bloquer ou débloquer un utilisateur
    $blockUserMessage = "";
    if (isset($_POST['block_user'])) {
        $user_id = $_POST['user_id'];

        // Récupérer l'état de blocage actuel de l'utilisateur
        $select_query = "SELECT is_blocked FROM users WHERE user_id = $user_id";
        $result = $conn->query($select_query);
        $row = $result->fetch_assoc();
        $is_blocked = $row['is_blocked'];

        // Inverser l'état de blocage de l'utilisateur
        $new_block_status = ($is_blocked == 1) ? 0 : 1;

        // Mettre à jour l'état de blocage de l'utilisateur dans la base de données
        $update_query = "UPDATE users SET is_blocked = $new_block_status WHERE user_id = $user_id";
        $conn->query($update_query);

        // Message de succès pour l'alerte
        $blockUserMessage = ($new_block_status == 1) ? "L'utilisateur a été bloqué avec succès." : "L'utilisateur a été débloqué avec succès.";
    }

    // Récupérer tous les utilisateurs de la base de données
    $select_query = "SELECT * FROM users";

    // Vérifier si une recherche par nom d'utilisateur est effectuée
    if (isset($_GET['username'])) {
        $username = $_GET['username'];
        // Ajouter une condition WHERE pour filtrer les utilisateurs par nom d'utilisateur
        $select_query .= " WHERE uidUsers LIKE '%$username%'";
    }

    $result = $conn->query($select_query);

    require "header.php";
    ?><br><br><br>

    
    <div class="container">
        <h1 class="text-center" style="background-color:  #00B2A9; border-radius: 15px; color: white; padding: 10px;">Gestion des utilisateurs</h1>

        <!-- Formulaire de recherche -->
        <form method="get" action="">
            <div class="form-group">
                <label for="username">Rechercher par nom d'utilisateur :</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Nom d'utilisateur">
            </div>
            <button class="btn btn" style="color: black;" type="submit"><i class="fa fa-search"></i></button>
        </form><br>
  <!-- Afficher l'alerte de changement de rôle -->
  <?php if (!empty($changeRoleMessage)) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $changeRoleMessage; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php } ?>

    <!-- Afficher l'alerte de suppression d'utilisateur -->
    <?php if (!empty($deleteUserMessage)) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $deleteUserMessage; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php } ?>

    <!-- Afficher l'alerte de blocage/déblocage d'utilisateur -->
    <?php if (!empty($blockUserMessage)) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $blockUserMessage; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php } ?>

<?php
} else {
    echo "<p>Accès refusé. Vous devez être connecté en tant qu'administrateur pour accéder à cette page.</p>";
}
?>
        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-striped">
                <thead style="background-color: #00B2A9; color: white;">
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['uidUsers']; ?></td>
                            <td><?php echo $row['emailUsers']; ?></td>
                            <td><?php echo $row['reg_date']; ?></td>
                            <td><?php echo ($row['role_id'] == 1 ? 'Utilisateur' : 'Admin'); ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">

                                    <!-- Bouton pour changer le rôle -->
                                    <button class="btn btn-primary" type="submit" name="change_role"><i class="fa fa-exchange"></i> Changer rôle</button>&nbsp;

                                    <!-- Bouton pour supprimer l'utilisateur -->
                                    <button class="btn btn-danger" type="submit" name="delete_user"><i class="bi-person-dash"></i> Supprimer</button>&nbsp;

                                    <!-- Bouton pour bloquer ou débloquer l'utilisateur -->
                                    <?php
                                    $block_button_text = ($row['is_blocked'] == 1) ? 'Débloquer' : 'Bloquer';
                                    ?>
                                    <button class="btn btn-warning" type="submit" name="block_user">
                                        <?php if ($row['is_blocked'] == 1) { ?>
                                            <i class="fa fa-unlock"></i> Débloquer
                                        <?php } else { ?>
                                            <i class="fa fa-lock"></i> Bloquer
                                        <?php } ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php } ?>
    </div>

    <?php
    // Fermer la connexion à la base de données
    $conn->close();
    ?>