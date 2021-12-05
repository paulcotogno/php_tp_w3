<?php
ob_start();

session_start();

if (isset($_SESSION['id'])) {
?>
    <div>
        <p>Vous êtes déja connecter</p>
        <form action="sessionEnd.php" method="post">
            <input type="submit" value="Se deconnecter">
        </form>
    </div>
<?php
} else {
?>
    <div>
        <form action="sessionStart.php" method="post">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1">
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
    </div>
<?php
}
$content = ob_get_clean();
require_once("template.php");
?>