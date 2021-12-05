<?php
ob_start();

session_start();

require '../../vendor/autoload.php';

use App\Config\Database;
use App\Manager\UserManager;

$um = new UserManager(Database::getConnection());

if (isset($_SESSION['id'])) {

    if (isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'])) {

        if (isset($_POST['isAdmin']) && $_POST['isAdmin'] == 'on') {
            $input = array(
                'firstName' => $_POST['firstName'],
                'lastName' => $_POST['lastName'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'isAdmin' => 1
            );
        } else {
            $input = array(
                'firstName' => $_POST['firstName'],
                'lastName' => $_POST['lastName'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'isAdmin' => 0
            );
        }

        $um->updateUser($input, $_SESSION['id']);

        header('Location: http://localhost:5555/app/view/account.php');
        exit();

    } else {

        $account = $um->getUser($_SESSION['id']);

?>
        <div class="d-flex justify-content-center h-100 align-items-center">
            <form method="post" class="w-50">
                <div class="card">
                    <h6 class="card-header">Modifier vos informations</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex flex-column">
                            <label for="inputFirst" class="form-label">First name</label>
                            <input type="text" name="firstName" class="form-control" id="inputFirst" value="<?= $account["firstName"] ?>">
                        </li>
                        <li class="list-group-item d-flex flex-column">
                            <label for="inputLast" class="form-label">Last name</label>
                            <input type="text" name="lastName" class="form-control" id="inputLast" value="<?= $account["lastName"] ?>">
                        </li>
                        <li class="list-group-item d-flex flex-column">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="text" name="email" class="form-control" id="inputEmail" value="<?= $account["email"] ?>">
                        </li>
                        <li class="list-group-item d-flex flex-column">
                            <label for="inputPassword" class="form-label">Password</label>
                            <input type="text" name="password" class="form-control" id="inputPassword" value="<?= $account["password"] ?>">
                        </li>
                        <li class="list-group-item d-flex flex-column">
                            <label for="boxAdmin" class="form-label">Administrateur</label>
                            <?php if ($account['isAdmin'] == 0) {
                            ?>
                                <input type="checkbox" name="isAdmin" id="boxAdmin">
                            <?php
                            } else {
                            ?>
                                <input type="checkbox" checked name="isAdmin" id="boxAdmin">
                            <?php
                            }
                            ?>
                        </li>
                    </ul>
                    <div class="card-footer d-flex justify-content-center">
                        <input class="btn btn-secondary ms-3" type="submit" value="Modifier">
                    </div>
                </div>
            </form>
        </div>
    <?php
    }
} else {
    ?>
    <div>
        <p>vous devez être connecter pour accéder à cette page</p>
    </div>
<?php
}
$content = ob_get_clean();
require_once("template.php");
?>