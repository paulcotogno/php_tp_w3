<?php

session_start();

$_SESSION = array();
session_unset();
session_destroy();
header('Location: http://localhost:5555/app/view/logIn.php');
exit();
