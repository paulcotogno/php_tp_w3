<?php

session_start();

require '../../vendor/autoload.php';

use App\Config\Database;
use App\Manager\PostManager;
use App\Manager\UserManager;
use App\Manager\CommentManager;

ob_start();

$isadmin = true;


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$pm = new PostManager(Database::getConnection());
$um = new UserManager(Database::getConnection());
$cm = new CommentManager(Database::getConnection());

if (isset($_SESSION['id'])) {
    if (isset($uri[4])) {
        $id = $uri[4];
        $post = $pm->getPost($id);

        if ($post) {
            $user = $um->getUser($post['userId']);
            $comments = $cm->getCommentsByPost($post['id']);
        } else {
            header("HTTP/1.1 404 Not Found");
            return;
        }
    } else {
        header("HTTP/1.1 404 Not Found");
        return;
    }

    if (isset($_POST['modifyPost'], $_POST['newTitle'], $_POST['newContent'])) {
        echo 'chuit';

        var_dump($_FILES['fileToChange']);

        if (isset($_FILES['fileToChange'])) {
            $tmp_name = $_FILES['fileToChange']["tmp_name"];
            $name = basename($_FILES['fileToChange']["name"]);
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/app/assets/img/";

            echo 'hello ?';

            if (is_dir($upload_dir) && is_writable($upload_dir)) {
                $moved = move_uploaded_file($_FILES["fileToChange"]["tmp_name"], $upload_dir . $name);
                $imageLink = "/app/assets/img/" . $name;
                if ($moved) {
                    $input = array(
                        'imageLink' => $imageLink,
                        'title' => $_POST['newTitle'],
                        'content' => $_POST['newContent']
                    );
                    echo 'pouet' . $input;
                } else {
                    echo "Not uploaded because of error #" . $_FILES["file"]["error"];
                }
            } else {
                echo 'Upload directory is not writable, or does not exist.';
            }
        } else {
            $input = array(
                'title' => $_POST['newTitle'],
                'content' => $_POST['newContent'],
                'imageLink' => $post['imageLink']
            );
            echo 'hard pouet';
            var_dump($input);
        }

        $pm->updatePost($input, $_POST['modifyPost']);

        header('Location: http://localhost:5555/app/view/Home.php');
        exit();
    } else if (isset($_POST['modifyPostScreen'])) {
        echo 'modify post';
?>
        <div class="d-flex align-items-center flex-column px-3 bg-light pt-4">
            <div class="card w-50">
                <form action="" method="post" enctype="multipart/form-data">
                    <h6 class="card-header">Posté par <?= $user['firstName'] . " " . $user['lastName']; ?></h6>
                    <img alt="" class="w-100" src="<?= $post['imageLink'] ?>" />
                    <label for="fileToChange" class="form-label">Changer la photo du post</label>
                    <input type="file" name="fileToChange" id="fileToChange">
                    <div class="card-body">
                        <label for="title">Changer le titre du post</label> <br />
                        <input class="card-title" id="title" name="newTitle" value="<?= $post['title'];
                                                                                    ?>"><br />
                        <label for="content">Changer le contenu du post</label><br />
                        <textarea class="card-text w-100" id="content" type="text" name="newContent"><?= $post['content']; ?></textarea>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><?= $post['date'] ?></li>
                        <li class="list-group-item d-flex">
                            <input type="text" name="modifyPost" class="d-none" value="<?= $post['id']; ?>">
                            <input type="submit" class="btn btn-primary" value="Modifier">
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    <?php
    } else if (isset($_POST['deletePost'])) {
        $pm->deletePost($_POST['deletePost']);

        header('Location: http://localhost:5555/app/View/Home.php');
        exit();
    } else if (isset($_POST['modifyCom'], $_POST['newComment'])) {

        $input = array(
            'content' => $_POST['newComment']
        );

        $cm->updateComment($input, $_POST['modifyCom']);

        header('Location: http://localhost:5555/app/View/Home.php');
        exit();
    } else if (isset($_POST['deleteCom'])) {

        $cm->deleteComment($_POST['deleteCom']);

        header('Location: http://localhost:5555/app/View/Home.php');
        exit();
    } else if (isset($_POST['commentaire'], $_POST['postId'])) {

        $input = array(
            'content' => $_POST['commentaire'],
            'postId' => $_POST['postId'],
            'userId' => $_SESSION['id'],
            'date' => date('Y-m-d')
        );

        $comment = $cm->createComment($input);

        header('Location: http://localhost:5555/app/View/Home.php');
        exit();
    } else {
    ?>
        <div class="d-flex align-items-center flex-column px-3 bg-light pt-4">
            <div class="card w-50">
                <h6 class="card-header">Posté par <?php echo $user['firstName'] . " " . $user['lastName']; ?></h6>
                <img alt="" src="<?= $post['imageLink']; ?>" />
                <div class="card-body">
                    <h5 class="card-title"><?= $post['title'] ?></h5>
                    <p class="card-text"><?= $post['content'] ?></p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><?= $post['date'] ?></li>
                    <?php
                    $userIsAdmin = $um->getUser($_SESSION['id']);
                    if ($userIsAdmin['isAdmin'] == 1 || $_SESSION['id'] == $user['id']) {
                    ?>
                        <li class="list-group-item d-flex">
                            <form action="" method="post">
                                <input type="text" name="modifyPostScreen" class="d-none" value="<?= $post['id']; ?>">
                                <input type="submit" class="btn btn-primary" value="Modifier">
                            </form>
                            <form action="" method="post">
                                <input type="text" name="deletePost" class="d-none" value="<?= $post['id'] ?>">
                                <input type="submit" class="btn btn-danger ms-2" value="Supprimer">
                            </form>
                        </li>
                    <?php } ?>
                </ul>
                <div class="card-footer">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <form action="" method="post" class="d-flex">
                                <textarea name="commentaire" id="input1" class="w-100" rows="1" cols="48"></textarea>
                                <input type="text" class="d-none" name="postId" value="<?= $post['id']; ?>">
                                <input class="btn btn-secondary ms-3" type="submit" value="Commenter">
                            </form>
                        </li>
                        <li class="list-group-item">
                            <div class="mb-1">
                                <h3>Commentaires</h3>
                                <?php foreach ($comments as $comment) {
                                ?>
                                    <div class="d-flex flex-wrap">
                                        <div class="w-100 d-flex justify-content-between">
                                            <h6 class="comment-author"><?php $userCom = $um->getUser($comment['userId']);
                                                                        echo $userCom['firstName'] . ' ' . $userCom['lastName']; ?></h6>
                                            <?php
                                            $userIsAdmin = $um->getUser($_SESSION['id']);
                                            if ($userIsAdmin['isAdmin'] == 1 || $_SESSION['id'] == $userCom['id']) {
                                            ?>
                                                <div class="d-flex"><button class="btn btn-primary" onclick="document.getElementById('modifyComment<?= $comment['id'] ?>').style.display='block'">Ouvrir la modification</button>
                                                    <form action="" method="post">
                                                        <input type="text" name="deleteCom" class="d-none" value="<?= $comment['id'] ?>">
                                                        <input type="submit" class="btn btn-danger ms-2" value="Supprimer">
                                                    </form>
                                                <?php
                                            }
                                                ?>
                                                </div>
                                        </div>
                                        <hr />
                                        <p class="comment px-3 pt-1"><?= $comment['content'];
                                                                        ?>
                                        </p>
                                        <div style="display: none;" class="w-100" id="modifyComment<?= $comment['id'] ?>">
                                            <form action="" method="post">
                                                <textarea type="text" class="w-100" name="newComment"><?= $comment['content']; ?></textarea>
                                                <input type="text" name="modifyCom" class="d-none" value="<?= $comment['id']
                                                                                                            ?>">
                                                <input type="submit" class="btn btn-primary" value="Modifier">
                                            </form>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
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