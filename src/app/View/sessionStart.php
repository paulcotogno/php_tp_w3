<?php
session_start();

require '../../vendor/autoload.php';

use App\Manager\UserManager;
use App\Config\Database;

$um = new UserManager(Database::getConnection());

if (isset($_POST['email'], $_POST['password'])) {

    $user = $um->getUserByMailAndPsswrd($_POST['email'], $_POST['password']);

    if (count($user) == 1) {
        $_SESSION['id'] = $user[0]['id'];
        $authOK = true;

        header('Location: http://localhost:5555/app/view/logIn.php');
        exit();
    } else {
?>
        <div class="w-100 h-100">
            <p>Connection impossible veuillez réessayer</p>
        </div>
<?php
    }
}
