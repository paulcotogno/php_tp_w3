<?php
ob_start();

session_start();

require '../../vendor/autoload.php';

use App\Config\Database;
use App\Manager\PostManager;

$pm = new PostManager(Database::getConnection());

if (isset($_SESSION['id'])) {
if (isset($_POST['title'], $_POST['content'], $_FILES['fileToUpload'])) {

    if (isset($_FILES["fileToUpload"])) {
        $tmp_name = $_FILES["fileToUpload"]["tmp_name"];
        $name = basename($_FILES["fileToUpload"]["name"]);
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/app/assets/img/";

        if (is_dir($upload_dir) && is_writable($upload_dir)) {
            $moved = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $upload_dir . $name);
            $imageLink = "/app/assets/img/" . $name;
            if ($moved) {

                $input = array(
                    'title' => $_POST['title'],
                    'content' => $_POST['content'],
                    'date' => date('Y-m-d'),
                    'imageLink' => $imageLink,
                    'userId' => $_SESSION['id']
                );

                $pm->createPost($input);

                header('Location: http://localhost:5555/app/view/Home.php');
                exit();
            } else {
                echo "Not uploaded because of error #" . $_FILES["fileToUpload"]["error"];
            }
        } else {
            echo 'Upload directory is not writable, or does not exist.';
        }
    }
} else {
?>
    <div class="d-flex justify-content-center h-100 align-items-center">
        <form method="post" class="w-50" enctype="multipart/form-data">
            <div class="card">
                <h5 class="card-header"><?php //echo $Post['authorid']; 
                                        ?></h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex flex-column">
                        <label for="fileToUpload" class="form-label">Photo du post</label>
                        <input type="file" name="fileToUpload" id="fileToUpload">
                    </li>
                </ul>
                <div class="card-body">
                    <h5 class="card-title"><label for="inputTitle" class="form-label">Titre du post</label>
                        <input type="text" name="title" class="form-control" id="inputTitle">
                    </h5>
                    <p class="card-text">
                        <label for="inputContent" class="form-label">Contenu du poste</label>
                        <textarea type="text" name="content" class="form-control" id="inputContent"></textarea>
                    </p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <button type="submit" class="btn btn-primary">Créer le post</button>
                    </li>
                </ul>
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