<?php

require_once('../db.php');
require_once('../JWT.php');
include '../controller/login.php';

$login = new Login();

respond ($login->tryLogin($_POST['uname'],$_POST['pass']));

?>