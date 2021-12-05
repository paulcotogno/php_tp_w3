<?php
ob_start();

require '../../vendor/autoload.php';

$isAdmin = true;

use App\Config\Database;
use App\Manager\UserManager;
use App\Manager\CommentManager;

$um = new UserManager(Database::getConnection());
$cm = new CommentManager(Database::getConnection());

if (isset($_POST['deleteUser'])) {
    $um->deleteUser($_POST['deleteUser']);

    header('Location: http://localhost:5555/app/View/Home.php');
    exit();
} else if ($isAdmin) {
    $users = $um->getAllUsers();
?>
    <div class="alert alert-danger mb-1">Si vous supprimer un utilisateur, ces posts et commentaires seront automatiquement supprimer.</div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Prénom</th>
                <th scope="col">Nom</th>
                <th scope="col">Mail</th>
                <th scope="col">Rôle</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <th scope="row"><?= $user['firstName'] ?></th>
                    <td><?= $user['lastName'] ?></td>
                    <td><a class="primary" href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></td>
                    <td>
                        <?php if ($user['isAdmin'] == 1) {
                            echo "Administrateur";
                        } else {
                            echo "Utilisateur";
                        } ?>
                    </td>
                    <td class="d-flex">
                        <form action="" method="post" class="ms-2">
                            <input type="text" class="d-none" name="deleteUser" value="<?= $user['id'] ?>">
                            <input type="submit" class="btn btn-danger" value="Supprimer">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <tbody>
    <?php
}
$content = ob_get_clean();
require_once("template.php");
