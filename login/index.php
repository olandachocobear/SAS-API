<?php

require_once('../db.php');
include '../controller/login.php';

$login = new Login();

respond ($login->tryLogin($_GET['uname'],$_GET['pass']));

?>