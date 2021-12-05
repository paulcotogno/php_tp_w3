<?php
ob_start();

use App\Config\Database;
use App\Manager\UserManager;

require '../../vendor/autoload.php';

$um = new UserManager(Database::getConnection());

if (isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'])) {

    $input = array(
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName'],
        'isAdmin' => 0,
        'email' => $_POST['email'],
        'password' => $_POST['password']
    );

    $um->createUser($input);

    header('Location: http://localhost:5555/app/view/Home.php');
    exit();
} else {
?>
<div>
    <form method="post">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" name="firstName" class="form-control" id="firstName">
        </div>
        <div class="mb-3">
            <label for="LastName" class="form-label">Last Name</label>
            <input type="text" name="lastName" class="form-control" id="LastName">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</div>
<?php
}
$content = ob_get_clean();
require_once("template.php");
?>