<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <link rel="shortcut icon" type="image/ico" href="/app/assets/favicon.ico" />
  <title>Touittère</title>
</head>

<body style="height : 80vh;">
  <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="Home.php">
        <img src="/app/assets/logo.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
        Touittère
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="Home.php">Home</a>
          </li>
          <?php
          if (isset($_SESSION['id'])) {
          ?>
            <li class="nav-item">
              <a class="nav-link" href="account.php">Account</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="listUser.php">Users</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="createPost.php">Create post</a>
            </li>
          <?php
          }
          ?>
        </ul>
      </div>
      <?php
      if (isset($_SESSION['id'])) {
      ?>
        <p><a href="http://localhost:5555/app/view/logIn.php">Hello user n° : <?= $_SESSION['id'] ?></a></p>
      <?php
      } else {
      ?>
        <form class="d-flex">
          <a class="btn btn-outline-info me-2" type="submit" href="signIn.php">Sign In</a>
          <a class="btn btn-outline-info" type="submit" href="logIn.php">Log In</a>
        </form>
      <?php
      }
      ?>
    </div>
  </nav>
  <div class="container h-100">
    <?= $content ?>
  </div>
</body>

</html>