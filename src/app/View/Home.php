<?php

session_start();

require '../../vendor/autoload.php';

use App\Config\Database;
use App\Manager\CommentManager;
use App\Manager\PostManager;
use App\Manager\UserManager;

ob_start();

$cm = new CommentManager(Database::getConnection());
$pm = new PostManager(Database::getConnection());
$um = new UserManager(Database::getConnection());


$posts = $pm->getAllPosts();

if (isset($_SESSION['id'])) {
    if (isset($_POST['content'], $_POST['postId'])) {

        $input = array(
            'content' => $_POST['content'],
            'postId' => $_POST['postId'],
            'userId' => $_SESSION['id'],
            'date' => date('Y-m-d')
        );

        $comment = $cm->createComment($input);

        header('Location: http://localhost:5555/app/View/Home.php');
        exit();
    } else {
?>
        <div class="post-container">
            <div class="d-flex align-items-center flex-column px-3 bg-light pt-4">
                <?php foreach ($posts as $post) {
                    $user = $um->getUser($post['userId']);
                ?>
                    <div class="card w-50 my-3">
                        <h6 class="card-header">
                            <?= $user['firstName'] . " " . $user['lastName'] ?>
                        </h6>

                        <a href="Post.php/<?= $post['id'] ?>">
                            <img alt="" class="w-100" src="<?= $post['imageLink'] ?>" />
                            <div class="card-body">
                                <h5 class="card-title"><?= $post['title'] ?></h5>
                                <p class="card-text"><?= $post['content'] ?></p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><?= $post['date'] ?></li>
                            </ul>
                        </a>
                        <div class="card-footer">
                            <form action="" method="post" class="d-flex">
                                <textarea name="content" id="input1" class="w-100" rows="1" cols="48"></textarea>
                                <input type="text" name="postId" class="d-none" value="<?= $post['id'] ?>">
                                <input class="btn btn-secondary ms-3" type="submit" value="Commenter">
                            </form>
                        </div>
                    </div>
                <?php }
                ?>
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